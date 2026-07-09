@extends('errors::minimal')

@section('title', __('Error del Servidor'))
@section('code', '500')
@section('message', __('Error Interno del Servidor'))
@section('description', __('Algo salió mal en nuestros servidores. Ya estamos trabajando para solucionarlo lo antes posible.'))
@section('icon', 'bi-exclamation-octagon-fill') 
