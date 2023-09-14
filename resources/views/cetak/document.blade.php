@extends('layouts.cetak')
@section('content')
  isi cetak
  {{$foo}} 
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>1</th>
        <th>2</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>2</td>
      </tr>
    </tbody>
  </table>
@endsection
