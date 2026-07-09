@extends('errors::minimal')

@section('title', __('Acceso Prohibido'))
@section('code', '403')
@section('message', __('Acceso Restringido'))
@section('description', __('No tienes permisos suficientes para ver este recurso o realizar esta acción.'))
@section('icon', 'bi-shield-slash-fill')
