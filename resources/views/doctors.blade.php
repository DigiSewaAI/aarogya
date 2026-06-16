<!-- resources/views/doctors.blade.php -->
@extends('layouts.app')

@section('title', 'डाक्टरहरू')

@section('content')
<div style="padding: 30px;">
    <h1>👨‍⚕️ हाम्रा डाक्टरहरू</h1>
    
    <!-- Doctor listing yaha add garne -->
    @if(isset($doctors) && count($doctors) > 0)
        @foreach($doctors as $doctor)
            <div style="border: 1px solid #ddd; padding: 15px; margin: 10px 0;">
                <h3>{{ $doctor->name }}</h3>
                <p>विशेषता: {{ $doctor->specialization }}</p>
                <p>शुल्क: रु {{ $doctor->fee }}</p>
                <p>अनुभव: {{ $doctor->experience }} वर्ष</p>
            </div>
        @endforeach
    @else
        <p>डाक्टरहरू उपलब्ध छैनन्</p>
    @endif
</div>
@endsection