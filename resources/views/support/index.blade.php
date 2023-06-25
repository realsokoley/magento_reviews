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

            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            What are the main App features?
                        </button>
                    </h2>
                </div>

                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#supportAccordion">
                    <div class="card-body">
                        The app includes vocabulary lists divided into three complexity levels: basic, intermediate, and advanced. Each word in the lists is accompanied by examples and exercises to ensure that you understand not just the meaning but also the context in which it can be used. The dialogues feature offers you an immersive experience, allowing you to understand how the words can be used in everyday conversations.
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Is it possible to adjust word lists?
                        </button>
                    </h2>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#supportAccordion">
                    <div class="card-body">
                        At the moment, customizing word lists is not a feature that is available. However, we understand the importance of tailoring the learning experience to individual needs, so we are working on introducing this feature in the near future.
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            How is the rating counted?
                        </button>
                    </h2>
                </div>

                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#supportAccordion">
                    <div class="card-body">
                        The rating system in OVI is based on the exercises you complete. The more exercises you finish, the higher your rating becomes. This is designed to encourage consistent learning and practice.
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            What are the plans for improving the app?
                        </button>
                    </h2>
                </div>

                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#supportAccordion">
                    <div class="card-body">
                        We have several exciting plans for the future development of OVI. This includes expanding the range of languages that the app supports, introducing a feature that allows users to create their own topics with AI-generated exercises and dialogues, and implementing a subscription system for additional features and content. Stay tuned for these updates!
                    </div>
                </div>
            </div>

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
