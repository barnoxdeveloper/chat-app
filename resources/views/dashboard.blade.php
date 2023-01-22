<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Chat App</title>
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
            integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <style>
            section {
                padding: 50px 0;
            }
            section .scroll {
                height: 500px;
                overflow-y: scroll;
                padding: 5px;
            }

            p {
                overflow-wrap: break-word;
            }

        </style>
    </head>
    <body>
        <header>
            <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary bg-dark" data-bs-theme="dark">
                <div class="container">
                    <a class="navbar-brand" href="#">{{ config('app.name', 'Laravel') }}</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                {{-- <a class="nav-link active" aria-current="page" href="#">Chat Room</a> --}}
                            </li>
                        </ul>
                        <ul class="navbar-nav ms-auto align-self-end">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button class="dropdown-item text-danger" type="submit">{{ __('Log Out') }}</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        
        <section>
            <div class="container mt-5">
                {{-- textarea --}}
                <div class="row justify-content-center">
                    <div class="col-10 shadow p-3 mb-5 bg-body-tertiary rounded">
                        <form action="" id="form-post">
                            <div class="form-floating">
                                <textarea name="message" class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                                <label for="floatingTextarea2">Comments</label>
                                <span id="error-message" class="text-danger text-error mt-3"></span>
                            </div>
                            <div class="form-group text-center mt-3">
                                <button type="submit" class="btn btn-primary" id="btn-save">
                                    <i class="far fa-paper-plane"></i>
                                    Send
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- chat --}}
                <div class="row justify-content-between rounded">
                    <div class="col-12 shadow p-3 mb-5 bg-body-tertiary rounded">
                        <div class="scroll">
                            <div class="cardData"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function () {
                // fetch data
                $('#form-post').trigger("reset");
                setInterval(display, 3000);

                function display() {
                    // clear();
                    $.ajax({
                        url: "{{ route('chirps.index') }}",
                        type: 'GET',
                        success: function(response) {
                            $(".cardData").remove();
                            // loop through the data
                            response.forEach(function(item) {
                                let event = new Date(Date(item.created_at));
                                let html = `
                                    <div class="cardData">
                                        <span>${item.user.name}</span> |
                                        <small class="text-sm text-gray">${moment(item.created_at).format('h:mm:ss a, DD-MM-YYYY')}</small>
                                        <p>${item.message}</p>
                                        <hr>
                                    </div>
                                    `;
                                $('.scroll').append(html);
                            });
                        }
                    });
                }
    
                // create data
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    }
                });
                $("#form-post").submit(function(e) {
                    e.preventDefault();
                    if ($("#form-post").length > 0) {
                        $("#form-post").validate({
                            submitHandler: function (form) {
                                let formData = new FormData(document.getElementById('form-post'));
                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('chirps.store') }}",
                                    data: formData,
                                    dataType: 'json',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: function(data) {
                                        if (data.code == 0) {
                                            $('span').text(data.messages.message);
                                            // alert();
										} else if (data.code == 200) {
                                            $('#form-post').trigger("reset");
                                            $('span').hide();
                                            Swal.fire({
                                                position: 'top-end',
                                                icon: 'success',
                                                title: 'Your messages has been send',
                                                showConfirmButton: false,
                                                timer: 1000
                                            });
                                            $(".cardData").remove();
                                            display();
                                        }
                                    },
                                    error: function (data) {
										$.each(data.messages, function(prefix, val) {
											$('p.'+prefix+'_error').text(val[0]);
										});
									}
                                });
                            }
                        });
                    }
                });
            });
        </script>
    </body>
</html>