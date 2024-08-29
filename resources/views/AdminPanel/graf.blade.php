@extends('AdminPanel.layouts.master')
@section('content')
@include('AdminPanel.statistics.units')
@include('AdminPanel.statistics.totals')
@include('AdminPanel.statistics.clientResources')
@include('AdminPanel.statistics.main')
@include('AdminPanel.statistics.scripts', ['unit' => $unit,'numberOfStatus' => $numberOfStatus,'sourceNum' => $sourceNum,'statistic' => $statistic])
@endsection
