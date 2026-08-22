@extends('errors::minimal')

@section('title', __('Servicio No Disponible'))
@section('code', '503')
@section('message', __('Servicio en Mantenimiento'))
@section('description', __('Estamos realizando mejoras y mantenimiento programado. Estaremos de vuelta muy pronto.'))
@section('icon', 'bi-tools')
