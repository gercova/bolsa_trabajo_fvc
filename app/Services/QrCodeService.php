<?php

namespace App\Services;

class QrCodeService
{
    /**
     * Galois Field 256 tables for Reed-Solomon encoding (polynomial 0x11D = 285).
     */
    protected static array $exp = [];

    protected static array $log = [];

    protected static bool $initialized = false;

    /**
     * Alignment pattern center coordinates by version (1 to 10).
     */
    protected static array $alignmentCoords = [
        1 => [],
        2 => [6, 18],
        3 => [6, 22],
        4 => [6, 26],
        5 => [6, 30],
        6 => [6, 34],
        7 => [6, 22, 38],
        8 => [6, 24, 42],
        9 => [6, 26, 46],
        10 => [6, 28, 50],
    ];

    /**
     * Capacity and Error Correction Table for EC Level M (15% recovery):
     * [totalCodewords, dataCodewords, ecCodewordsPerBlock, numBlocks]
     */
    protected static array $versionSpecs = [
        1 => ['total' => 26,  'data' => 16,  'ecPerBlock' => 10, 'blocks' => 1],
        2 => ['total' => 44,  'data' => 28,  'ecPerBlock' => 16, 'blocks' => 1],
        3 => ['total' => 70,  'data' => 44,  'ecPerBlock' => 26, 'blocks' => 1],
        4 => ['total' => 100, 'data' => 64,  'ecPerBlock' => 18, 'blocks' => 2],
        5 => ['total' => 134, 'data' => 86,  'ecPerBlock' => 24, 'blocks' => 2],
        6 => ['total' => 172, 'data' => 108, 'ecPerBlock' => 16, 'blocks' => 4],
        7 => ['total' => 196, 'data' => 124, 'ecPerBlock' => 18, 'blocks' => 4],
        8 => ['total' => 242, 'data' => 154, 'ecPerBlock' => 22, 'blocks' => 4],
        9 => ['total' => 292, 'data' => 182, 'ecPerBlock' => 22, 'blocks' => 5],
        10 => ['total' => 346, 'data' => 216, 'ecPerBlock' => 26, 'blocks' => 5],
    ];

    /**
     * Initialize Galois Field log and antilog tables.
     */
    protected static function initGf(): void
    {
        if (self::$initialized) {
            return;
        }

        self::$exp = array_fill(0, 512, 0);
        self::$log = array_fill(0, 256, 0);

        $val = 1;
        for ($i = 0; $i < 255; $i++) {
            self::$exp[$i] = $val;
            self::$log[$val] = $i;
            $val <<= 1;
            if ($val & 0x100) {
                $val ^= 0x11D; // x^8 + x^4 + x^3 + x^2 + 1
            }
        }
        for ($i = 255; $i < 512; $i++) {
            self::$exp[$i] = self::$exp[$i - 255];
        }

        self::$initialized = true;
    }

    /**
     * Multiply two numbers in GF(256).
     */
    protected static function gfMul(int $x, int $y): int
    {
        if ($x === 0 || $y === 0) {
            return 0;
        }

        return self::$exp[self::$log[$x] + self::$log[$y]];
    }

    /**
     * Compute Reed-Solomon error correction codewords.
     */
    protected static function computeReedSolomon(array $data, int $ecCount): array
    {
        self::initGf();

        // Generate generator polynomial
        $generator = [1];
        for ($i = 0; $i < $ecCount; $i++) {
            $next = array_fill(0, count($generator) + 1, 0);
            $factor = self::$exp[$i];
            for ($j = 0; $j < count($generator); $j++) {
                $next[$j] ^= $generator[$j];
                $next[$j + 1] ^= self::gfMul($generator[$j], $factor);
            }
            $generator = $next;
        }

        // Polynomial long division
        $remainder = array_merge($data, array_fill(0, $ecCount, 0));
        for ($i = 0; $i < count($data); $i++) {
            $lead = $remainder[$i];
            if ($lead !== 0) {
                for ($j = 0; $j < count($generator); $j++) {
                    $remainder[$i + $j] ^= self::gfMul($generator[$j], $lead);
                }
            }
        }

        return array_slice($remainder, count($data), $ecCount);
    }

    /**
     * Choose the minimum QR version (1 to 10) for byte-mode data.
     */
    protected static function chooseVersion(int $dataLength): int
    {
        foreach (self::$versionSpecs as $ver => $spec) {
            // In byte mode: 4 bits mode + 8 bits length (for ver 1-9) + 8*dataLength bits
            $overhead = ($ver <= 9) ? 2 : 3; // bytes overhead (mode + char count indicator)
            if ($dataLength + $overhead <= $spec['data']) {
                return $ver;
            }
        }

        return 10;
    }

    /**
     * Encode payload string into byte stream for the QR code.
     */
    protected static function encodeData(string $text, int $version): array
    {
        $dataBytes = array_values(unpack('C*', $text));
        $len = count($dataBytes);
        $totalDataCapacity = self::$versionSpecs[$version]['data'];

        // Bit stream builder
        $bitStream = '';

        // 1. Mode indicator: Byte mode is 0100
        $bitStream .= '0100';

        // 2. Character count indicator: 8 bits for version 1-9, 16 bits for version 10
        $charCountBits = ($version <= 9) ? 8 : 16;
        $bitStream .= str_pad(decbin($len), $charCountBits, '0', STR_PAD_LEFT);

        // 3. Data bits
        foreach ($dataBytes as $b) {
            $bitStream .= str_pad(decbin($b), 8, '0', STR_PAD_LEFT);
        }

        // 4. Terminator (up to 4 zero bits)
        $maxBits = $totalDataCapacity * 8;
        $diff = $maxBits - strlen($bitStream);
        if ($diff > 0) {
            $terminatorLen = min(4, $diff);
            $bitStream .= str_repeat('0', $terminatorLen);
        }

        // 5. Pad to next byte boundary
        while (strlen($bitStream) % 8 !== 0) {
            $bitStream .= '0';
        }

        // 6. Convert bits to bytes
        $bytes = [];
        for ($i = 0; $i < strlen($bitStream); $i += 8) {
            $bytes[] = bindec(substr($bitStream, $i, 8));
        }

        // 7. Pad bytes with 0xEC (236) and 0x11 (17) until capacity reached
        $pad = [236, 17];
        $padIdx = 0;
        while (count($bytes) < $totalDataCapacity) {
            $bytes[] = $pad[$padIdx % 2];
            $padIdx++;
        }

        return $bytes;
    }

    /**
     * Interleave data and error-correction blocks.
     */
    protected static function interleave(array $dataBytes, int $version): array
    {
        $spec = self::$versionSpecs[$version];
        $numBlocks = $spec['blocks'];
        $ecPerBlock = $spec['ecPerBlock'];
        $dataPerBlock = intdiv($spec['data'], $numBlocks);
        $extraDataBlocks = $spec['data'] % $numBlocks;

        $dataBlocks = [];
        $ecBlocks = [];

        $offset = 0;
        for ($b = 0; $b < $numBlocks; $b++) {
            $blockSize = $dataPerBlock + ($b >= ($numBlocks - $extraDataBlocks) ? 1 : 0);
            $block = array_slice($dataBytes, $offset, $blockSize);
            $dataBlocks[] = $block;
            $ecBlocks[] = self::computeReedSolomon($block, $ecPerBlock);
            $offset += $blockSize;
        }

        $result = [];
        $maxDataLen = max(array_map('count', $dataBlocks));
        for ($i = 0; $i < $maxDataLen; $i++) {
            for ($b = 0; $b < $numBlocks; $b++) {
                if (isset($dataBlocks[$b][$i])) {
                    $result[] = $dataBlocks[$b][$i];
                }
            }
        }

        for ($i = 0; $i < $ecPerBlock; $i++) {
            for ($b = 0; $b < $numBlocks; $b++) {
                $result[] = $ecBlocks[$b][$i];
            }
        }

        return $result;
    }

    /**
     * Generate the complete QR Code 2D matrix (true = black, false = white).
     */
    public static function generateMatrix(string $text): array
    {
        $version = self::chooseVersion(strlen($text));
        $size = 4 * $version + 17;

        $matrix = array_fill(0, $size, array_fill(0, $size, null));
        $isReserved = array_fill(0, $size, array_fill(0, $size, false));

        // 1. Finder patterns
        self::placeFinderPattern($matrix, $isReserved, 0, 0);
        self::placeFinderPattern($matrix, $isReserved, 0, $size - 7);
        self::placeFinderPattern($matrix, $isReserved, $size - 7, 0);

        // 2. Alignment patterns for version >= 2
        $coords = self::$alignmentCoords[$version] ?? [];
        foreach ($coords as $r) {
            foreach ($coords as $c) {
                // Skip if overlapping with finder patterns
                if (($r <= 8 && $c <= 8) || ($r <= 8 && $c >= $size - 8) || ($r >= $size - 8 && $c <= 8)) {
                    continue;
                }
                self::placeAlignmentPattern($matrix, $isReserved, $r - 2, $c - 2);
            }
        }

        // 3. Timing patterns
        for ($i = 8; $i < $size - 8; $i++) {
            if ($matrix[6][$i] === null) {
                $matrix[6][$i] = ($i % 2 === 0);
                $isReserved[6][$i] = true;
            }
            if ($matrix[$i][6] === null) {
                $matrix[$i][6] = ($i % 2 === 0);
                $isReserved[$i][6] = true;
            }
        }

        // 4. Dark module
        $matrix[4 * $version + 9][8] = true;
        $isReserved[4 * $version + 9][8] = true;

        // 5. Reserve format info areas
        for ($i = 0; $i < 9; $i++) {
            $isReserved[8][$i] = true;
            $isReserved[$i][8] = true;
            $isReserved[8][$size - 1 - $i] = true;
            $isReserved[$size - 1 - $i][8] = true;
        }

        // 6. Encode data + EC and place into matrix
        $encodedData = self::encodeData($text, $version);
        $codewords = self::interleave($encodedData, $version);

        // Convert codewords to bit array
        $bits = [];
        foreach ($codewords as $cw) {
            for ($b = 7; $b >= 0; $b--) {
                $bits[] = (bool) (($cw >> $b) & 1);
            }
        }

        // Add remainder bits if needed for version
        $remainderBits = ($version >= 2 && $version <= 6) ? 7 : 0;
        for ($r = 0; $r < $remainderBits; $r++) {
            $bits[] = false;
        }

        // Place bits in two-column zig-zag from right to left
        $bitIdx = 0;
        $bitCount = count($bits);
        $col = $size - 1;
        $goingUp = true;

        while ($col > 0) {
            if ($col === 6) { // Skip vertical timing pattern column
                $col--;
            }

            for ($step = 0; $step < $size; $step++) {
                $row = $goingUp ? ($size - 1 - $step) : $step;

                for ($c = 0; $c < 2; $c++) {
                    $currCol = $col - $c;
                    if (! $isReserved[$row][$currCol]) {
                        $matrix[$row][$currCol] = ($bitIdx < $bitCount) ? $bits[$bitIdx] : false;
                        $bitIdx++;
                    }
                }
            }

            $goingUp = ! $goingUp;
            $col -= 2;
        }

        // 7. Apply standard mask 0: (row + col) % 2 === 0
        $maskPattern = 0;
        for ($r = 0; $r < $size; $r++) {
            for ($c = 0; $c < $size; $c++) {
                if (! $isReserved[$r][$c]) {
                    if (($r + $c) % 2 === 0) {
                        $matrix[$r][$c] = ! $matrix[$r][$c];
                    }
                }
            }
        }

        // 8. Place format info: EC level M (00) + Mask 0 (000) = 00000 -> with BCH + XOR 0x5412 = 0x5412 = 101010000010010b
        $formatBits = [1, 0, 1, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0];
        self::placeFormatInfo($matrix, $formatBits, $size);

        return $matrix;
    }

    protected static function placeFinderPattern(array &$matrix, array &$isReserved, int $top, int $left): void
    {
        for ($r = -1; $r <= 7; $r++) {
            for ($c = -1; $c <= 7; $c++) {
                $row = $top + $r;
                $col = $left + $c;
                if ($row >= 0 && $row < count($matrix) && $col >= 0 && $col < count($matrix)) {
                    if ($r >= 0 && $r <= 6 && $c >= 0 && $c <= 6) {
                        $isBlack = ($r === 0 || $r === 6 || $c === 0 || $c === 6 || ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4));
                        $matrix[$row][$col] = $isBlack;
                    } else {
                        $matrix[$row][$col] = false; // Separator
                    }
                    $isReserved[$row][$col] = true;
                }
            }
        }
    }

    protected static function placeAlignmentPattern(array &$matrix, array &$isReserved, int $top, int $left): void
    {
        for ($r = 0; $r < 5; $r++) {
            for ($c = 0; $c < 5; $c++) {
                $row = $top + $r;
                $col = $left + $c;
                $isBlack = ($r === 0 || $r === 4 || $c === 0 || $c === 4 || ($r === 2 && $c === 2));
                $matrix[$row][$col] = $isBlack;
                $isReserved[$row][$col] = true;
            }
        }
    }

    protected static function placeFormatInfo(array &$matrix, array $formatBits, int $size): void
    {
        // Format bits around top-left finder
        $coordsTopLeft = [
            [8, 0], [8, 1], [8, 2], [8, 3], [8, 4], [8, 5], [8, 7], [8, 8],
            [7, 8], [5, 8], [4, 8], [3, 8], [2, 8], [1, 8], [0, 8],
        ];

        for ($i = 0; $i < 15; $i++) {
            [$r, $c] = $coordsTopLeft[$i];
            $matrix[$r][$c] = (bool) $formatBits[$i];
        }

        // Format bits around bottom-left and top-right finders
        $coordsBottomLeft = [
            [$size - 1, 8], [$size - 2, 8], [$size - 3, 8], [$size - 4, 8], [$size - 5, 8], [$size - 6, 8], [$size - 7, 8],
        ];
        $coordsTopRight = [
            [8, $size - 8], [8, $size - 7], [8, $size - 6], [8, $size - 5], [8, $size - 4], [8, $size - 3], [8, $size - 2], [8, $size - 1],
        ];

        for ($i = 0; $i < 7; $i++) {
            [$r, $c] = $coordsBottomLeft[$i];
            $matrix[$r][$c] = (bool) $formatBits[$i];
        }
        for ($i = 0; $i < 8; $i++) {
            [$r, $c] = $coordsTopRight[$i];
            $matrix[$r][$c] = (bool) $formatBits[7 + $i];
        }
    }

    /**
     * Render QR code as sharp, scalable SVG.
     */
    public static function svg(string $text, int $size = 140, string $color = '#000000'): string
    {
        $matrix = self::generateMatrix($text);
        $moduleCount = count($matrix);
        $quietZone = 2; // quiet zone in modules
        $totalModules = $moduleCount + ($quietZone * 2);

        $path = '';
        for ($r = 0; $r < $moduleCount; $r++) {
            for ($c = 0; $c < $moduleCount; $c++) {
                if ($matrix[$r][$c]) {
                    $x = $c + $quietZone;
                    $y = $r + $quietZone;
                    $path .= "M{$x},{$y}h1v1h-1z ";
                }
            }
        }

        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$totalModules.' '.$totalModules.'" width="'.$size.'" height="'.$size.'" shape-rendering="crispEdges">'.
            '<rect width="100%" height="100%" fill="#ffffff"/>'.
            '<path d="'.rtrim($path).'" fill="'.htmlspecialchars($color, ENT_QUOTES).'"/>'.
            '</svg>';
    }

    /**
     * Render QR code as Data URI (base64 SVG).
     */
    public static function dataUri(string $text, int $size = 140, string $color = '#000000'): string
    {
        $svg = self::svg($text, $size, $color);

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }
}
