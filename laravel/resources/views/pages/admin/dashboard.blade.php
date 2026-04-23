@extends('layouts.app')

@section('content')
<h1>Admin Dashboard</h1>
<ul>
    <li>Total Orders: {{ $totalOrders }}</li>
    <li>Pending Orders: {{ $pendingOrders }}</li>
    <li>Menu Items: {{ $menuItems }}</li>
</ul>
@endsection
