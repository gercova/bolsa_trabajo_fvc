@extends('errors::minimal')

@section('title', __('Página Expirada'))
@section('code', '419')
@section('message', __('Sesión Expirada'))
@section('description', __('La sesión de esta página ha caducado por inactividad. Por favor, vuelve a cargar la página e intenta de nuevo.'))
@section('icon', 'bi-clock-history')
