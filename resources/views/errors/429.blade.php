@extends('errors::minimal')

@section('title', __('Demasiadas Solicitudes'))
@section('code', '429')
@section('message', __('Límite de Peticiones Excedido'))
@section('description', __('Has realizado demasiadas solicitudes en poco tiempo. Por favor, espera unos minutos antes de intentar de nuevo.'))
@section('icon', 'bi-hourglass-split')
