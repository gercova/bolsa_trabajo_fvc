@extends('errors::minimal')

@section('title', __('No Autorizado'))
@section('code', '401')
@section('message', __('Acceso No Autorizado'))
@section('description', __('Lo sentimos, necesitas iniciar sesión para acceder a esta página.'))
@section('icon', 'bi-shield-lock-fill')
