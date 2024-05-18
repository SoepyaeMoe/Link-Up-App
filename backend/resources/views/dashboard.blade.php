@extends('layout.app')
@section('title', 'Dashboard')
@section('dashboard-active', 'active')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ count($contents) }}</h3>

              <p>Contents</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-paper-plane"></i>
            </div>
            <a href="{{ url('contents') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>{{ count($users) }}</h3>

              <p>Users</p>
            </div>
            <div class="icon">
              <i class="fa-solid fa-users"></i>
            </div>
            <a href="{{ url('users') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{ count($admin_users) }}</h3>

              <p>Admins</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <a href="{{ url('admin-users') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>{{ count($histories) }}</h3>

              <p>History</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <a href="{{ url('admin-history') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
    </div>
  </section>
@endsection
