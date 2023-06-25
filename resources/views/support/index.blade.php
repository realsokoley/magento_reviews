@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h1 class="mb-5">OVI - Optimal Vocabulary Improvement Support</h1>

        <div class="jumbotron">
            <p class="lead">Welcome to the OVI support page. Here you can find help, answers to your questions, and contact us directly if you need more assistance.</p>
        </div>

        <div class="accordion" id="supportAccordion">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            How can I use the App to Improve my Finnish skills?
                        </button>
                    </h2>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#supportAccordion">
                    <div class="card-body">
                        OVI - Optimal Vocabulary Improvement offers a comprehensive approach to language learning. It is packed with lessons covering basic, intermediate, and advanced vocabulary levels. Each level includes word lists, examples of word usage in context, interactive exercises, and dialogues. By spending some time each day working through these materials, you can systematically improve your grasp of the Finnish language.
                    </div>
                </div>
            </div>

            <!-- Repeat the .card structure for each question -->

        </div>

        <h2 class="mt-5">Contact Us</h2>

        <form class="mt-4" method="POST" action="/support">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea class="form-control" id="message" name="message" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        @if (session('success'))
            <div class="alert alert-success mt-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endsection
