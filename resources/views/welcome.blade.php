@extends('layouts.web')
@section('title', $business->name)
@section('styles')
@endsection
@section('options')
@endsection
@section('preference')
@endsection
@section('content')


    <!-- Masthead-->
    <header class="masthead bg-secondary text-white text-center">
        <div class="container d-flex align-items-center flex-column">
            <!-- Masthead Avatar Image-->
            <img class="masthead-avatar mb-5" src="{{ asset('image/' . $business->logo) }}" alt="..." />
            <!-- Masthead Heading-->
            <h1 class="masthead-heading text-uppercase mb-0">Alan Carabali</h1>
            <!-- Icon Divider-->
            <div class="divider-custom divider-light">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-code"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- Masthead Subheading-->
            <p class="masthead-subheading font-weight-light mb-0">Backend Developer</p>
        </div>
    </header>
    <!-- Portfolio Section-->
    <section class="page-section portfolio" id="portfolio">
        <div class="container">
            <!-- Portfolio Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Portafolio</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-code"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- Portfolio Grid Items-->
            <div class="row justify-content-center">
                <!-- Portfolio Item 1-->
                @if (!is_null($proyectos) && $proyectos->isNotEmpty())
                    @foreach ($proyectos as $proyect)
                        <div class="col-md-6 col-lg-4 mb-5">
                            <div class="portfolio-item mx-auto" data-bs-toggle="modal"
                                data-bs-target="#portfolioModal{{ $proyect->id }}">
                                <div
                                    class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                    <div class="portfolio-item-caption-content text-center text-white"><i
                                            class="fas fa-plus fa-3x"></i></div>
                                </div>

                                <img class="img-fluid" src="{{ asset($proyect->image) }}" alt="..." />
                            </div>
                        </div>
                        <div class="portfolio-modal modal fade" id="portfolioModal{{ $proyect->id }}" tabindex="-1"
                            aria-labelledby="portfolioModal1" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header border-0"><button class="btn-close" type="button"
                                            data-bs-dismiss="modal" aria-label="Close"></button></div>
                                    <div class="modal-body text-center pb-5">
                                        <div class="container">
                                            <div class="row justify-content-center">
                                                <div class="col-lg-8">
                                                    <!-- Portfolio Modal - Title-->
                                                    <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0">
                                                        {{ $proyect->title }}</h2>
                                                    <!-- Icon Divider-->
                                                    <div class="divider-custom">
                                                        <div class="divider-custom-line"></div>
                                                        <div class="divider-custom-icon"><i class="fas fa-code"></i></div>
                                                        <div class="divider-custom-line"></div>
                                                    </div>
                                                    <!-- Portfolio Modal - Text-->
                                                    <h5 class="mb-4">{{ $proyect->description }}</h5>

                                                    <p class="">{!! $proyect->long_description !!}</p>
                                                    <a class="btn btn-primary" href="{{ $proyect->link }}" target="_blank">
                                                        <i class="fas fa-arrow-right fa-fw"></i>
                                                        Ver más
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No hay proyectos disponibles</p>
                @endif
                <!-- About Section Button-->
                <div class="text-center mt-4">
                    <a class="btn btn-xl btn-outline-dark" href="{{ route('proyects') }}">
                        <i class="fas fa-arrow-right me-2"></i>
                        Ver mas
                    </a>
                </div>
            </div>
        </div>
    </section>
    @include('__whatsapp')
    <!-- About Section-->
    <section class="page-section bg-primary text-white mb-0" id="about">
        <div class="container">
            <!-- About Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-white">Sobre mi</h2>
            <!-- Icon Divider-->
            <div class="divider-custom divider-light">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-code"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- About Section Content-->
            <div class="row">
                <div class="ms-auto">
                    <p class="lead">{!! $business->description !!}</p>
                </div>
            </div>

        </div>
    </section>
    <!-- Testimonials Section-->
    <section class="page-section" id="testimonials">
        <div class="container">
            <!-- About Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Testimonios</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-code"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- About Section Content-->
            <div class="row justify-content-center">
                @if (!is_null($clients) && $clients->isNotEmpty())
                    @foreach ($clients as $client)
                        <div class="col-12 col-sm-6 col-md-4 mb-4">
                            <div class="card border-0 shadow h-100">
                                <div class="card-body text-center">
                                    <img class="rounded-circle img-fluid mb-3" src="{{ asset($client->image) }}"
                                        alt="{{ $client->name }}" style="width: 120px; height: 120px; object-fit: cover;">
                                    <h5 class="card-title">{{ $client->name }}</h5>
                                    <p class="card-text text-muted">
                                        "{{ $client->description }}"
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No hay clientes disponibles</p>
                @endif
                <div class="text-center mt-4">
                    <a class="btn btn-xl btn-outline-dark" href="{{ route('clientes') }}">
                        <i class="fas fa-arrow-right me-2"></i>
                        Ver mas
                    </a>
                </div>
            </div>

        </div>
    </section>
    <!-- Contact Section-->
    <section class="page-section" style="background-color: #f7f7f7;" id="contact">
        <div class="container">
            <!-- Contact Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Contáctame</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-code"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- Contact Section Form-->
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7">
                    <form id="contactForm">
                        @csrf
                        <!-- Name input-->
                        <div class="form-floating mb-3">
                            <input class="form-control" id="name" name="name" type="text"
                                placeholder="Ingrese su nombre" required />
                            <label for="name">Nombre completo</label>
                            <div class="invalid-feedback">El nombre es requerido.</div>
                        </div>
                        <!-- Email address input-->
                        <div class="form-floating mb-3">
                            <input class="form-control" id="email" name="email" type="email"
                                placeholder="name@ejemplo.com" required />
                            <label for="email">Correo electrónico</label>
                            <div class="invalid-feedback">El correo es requerido.</div>
                        </div>
                        <!-- Phone number input-->
                        <div class="form-floating mb-3">
                            <input class="form-control" id="phone" name="phone" type="tel"
                                placeholder="(123) 456-7890" required />
                            <label for="phone">Teléfono</label>
                            <div class="invalid-feedback">El teléfono es requerido.</div>
                        </div>
                        <!-- Message input-->
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="message" name="message" placeholder="Ingrese su mensaje" style="height: 10rem"
                                required></textarea>
                            <label for="message">Mensaje</label>
                            <div class="invalid-feedback">El mensaje es requerido.</div>
                        </div>
                        <div class="d-none" id="submitSuccessMessage">
                            <div class="text-center mb-3">
                                <div class="fw-bolder">¡Envío exitoso!</div>
                            </div>
                        </div>
                        <div class="d-none" id="submitErrorMessage">
                            <div class="text-center text-danger mb-3">¡Error al enviar el mensaje!</div>
                        </div>
                        <!-- Submit Button-->
                        <button class="btn btn-primary btn-xl" id="submitButton" type="submit">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

{!! Html::script('afdeveloper/js/scripts.js') !!}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#contactForm').on('submit', function(event) {
            event.preventDefault();

            $('#submitButton').attr("disabled", true);

            var formData = $(this)
                .serialize();

            $.ajax({
                url: "{{ route('bandeja.store') }}",
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#submitSuccessMessage').removeClass('d-none');
                        $('#submitErrorMessage').addClass('d-none');
                        $('#contactForm')[0].reset();
                    } else {
                        $('#submitErrorMessage').removeClass('d-none');
                        $('#submitSuccessMessage').addClass('d-none');
                    }
                },
                error: function() {
                    $('#submitErrorMessage').removeClass('d-none');
                    $('#submitSuccessMessage').addClass('d-none');
                },
                complete: function() {
                    $('#submitButton').attr("disabled",
                        false);
                }
            });
        });
    });
</script>
