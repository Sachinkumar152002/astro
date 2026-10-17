@extends('layouts.app')

@section('content')
    <section class="middle-section">
        <div class="container">
            <div class="row">
                @include('main.account.partials.sidebar')
                <div class="col-md-9">
                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 box-white">
                                    <h4>My Wishlist</h4>
                                </div>
                                <div class="col-sm-12 col-md-12 box-white">
                                    <div class="bg-white">
                                        @if($courses)
                                            <table class="table table-sm">
                                                <thead>
                                                <tr class="tb-border">
                                                    <th width="20%">Name</th>
                                                    <th>Description</th>
                                                </tr>
                                                </thead>
                                                @foreach($courses as $course)
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <a href="{{route('course.detail', [$course->slug])}}">
                                                                            {{$course->name}}
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        {!! $course->short_description !!}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </table>
                                        @else
                                            <p>Wishlist is empty</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection