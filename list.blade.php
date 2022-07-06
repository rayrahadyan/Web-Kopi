<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name') }}</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

  <!-- Styles -->
  <style>
    html,
    body {
      background-color: #fff;
      color: #636b6f;
      font-family: 'Nunito', sans-serif;
      font-weight: 200;
      height: 100vh;
      margin: 0;
    }

    .full-height {
      height: 100vh;
    }

    .flex-center {
      align-items: center;
      display: flex;
      justify-content: center;
    }

    .position-ref {
      position: relative;
    }

    .top-right {
      position: absolute;
      right: 10px;
      top: 18px;
    }

    .content {
      text-align: center;
    }

    .title {
      font-size: 84px;
    }

    .links>a {
      color: #636b6f;
      padding: 0 25px;
      font-size: 13px;
      font-weight: 600;
      letter-spacing: .1rem;
      text-decoration: none;
      text-transform: uppercase;
    }

    .m-b-md {
      margin-bottom: 30px;
    }
  </style>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
    integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="/">{{ config('app.name') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01"
      aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="/">Home
            <span class="sr-only">(current)</span>
          </a>
        </li>

        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
            aria-expanded="false">Categories</a>
          <div class="dropdown-menu">
            @foreach ($cats as $cat)
            <a class="dropdown-item" href="#{{ $cat->id }}">{{ $cat->name }}</a>
            @endforeach
          </div>
        </li>

        @auth
          <li class="nav-item active">
            <a class="nav-link" href="/">Logged in as {{ auth()->user()->full_name }}</a>
          </li>

          <li class="nav-item active">
            <a class="nav-link" href="{{ route('logout') }}"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
          </li>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        @else
          <li class="nav-item active">
            <a class="nav-link" href="{{ route('login') }}">Login</a>
          </li>

          <li class="nav-item active">
            <a class="nav-link" href="{{ route('register') }}">Register</a>
          </li>
        @endauth
        {{-- <li class="nav-item">
          <a class="nav-link" href="#">Features</a>
        </li> --}}

      </ul>

    </div>
  </nav>

  <div class="container-fluid m-4">
    <div class="row">
      @if (session('message'))
        <div class="alert alert-success" style="color: red">
          {{ session('message') }}
        </div>
      @endif
    </div>

    <div class="row">
      <div class="col-md-12">
        @foreach ($cats as $cat)
          <h3 class="my-3" id="{{ $cat->id }}">{{ $cat->name }}</h3>
          <div class="row">
          @foreach ($cat->products as $d)
            <div class="col-md-3">

              <div class="card" style="width: 18rem;">
                <img src="{{ asset('storage/' . $d->image) }}" class="card-img-top" alt="...">
                <div class="card-body">
                  <h5 class="card-title">{{ $d->name }}</h5>
                  <p class="card-text">{{ $d->description }}</p>
                  <form action="{{ route('order', $d->getKey()) }}" method="post">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-primary btn-block">Order</button>
                  </form>
                </div>
              </div>
            </div>
          @endforeach
          </div>
        @endforeach
      </div>
    </div>

    <div class="row" style="margin-top:50px; margin-bottom:50px">
      <table class="table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
          <?php $total = 0; ?>
          @foreach ($trx as $t)
            <tr>
              <td>{{ $t->product->name }}</td>
              <td>{{ number_format($t->quantity, 0, ',', '.') }}</td>
              <td>IDR {{ number_format($t->price, 0, ',', '.') }}</td>
              <td>IDR {{ number_format($t->price * $t->quantity, 0, ',', '.') }}</td>
              <?php $total += $t->price * $t->quantity; ?>
              <td>
                <form action="{{ route('order.delete', $t->getKey()) }}" method="post">
                  @csrf
                  <input type="submit" name="_method" value="DELETE" class="btn btn-danger btn-sm">
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="col-md-6">&nbsp;</div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-6">
            Total
          </div>
          <div class="col-md-6">
            IDR {{ number_format($total, 0, ',', '.') }}
          </div>
        </div>
        @guest
          <div class="row mt-3">
            <div class="col-md-6">
              Nama
            </div>
            <div class="col-md-6">
              <input type="text" class="form-control is-invalid" id="name" name="name" placeholder="Ahmad Dhani"
                form="form-checkout" required>
            </div>
          </div>
        @endguest
        <div class="row mt-3">
          <div class="col-md-6">
            &nbsp;
          </div>
          <div class="col-md-6">
            <form action="{{ route('checkout') }}" method="post" id="form-checkout">
              @csrf
              <button type="submit" class="btn btn-primary btn-sm btn-block"
                onclick="return confirm('Are you sure?')">Checkout</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
