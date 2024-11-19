@extends('layouts.master')

@section('title', 'Static Toggle Example')

@section('content')

<div class="container mt-4">
    <h4>Static Toggle Example</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Verified</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>
                    <input 
                        type="checkbox" 
                        role="switch" 
                        class="toggle-class" 
                        data-toggle="toggle" 
                        data-style="slow" 
                        data-on="Verified" 
                        data-off="Not Verified" 
                    >
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>
                    <input 
                        type="checkbox" 
                        role="switch" 
                        class="toggle-class" 
                        data-toggle="toggle" 
                        data-style="slow" 
                        data-on="Verified" 
                        data-off="Not Verified" 
                        checked
                    >
                </td>
            </tr>
        </tbody>
    </table>
</div>

@endsection
