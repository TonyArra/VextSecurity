
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="shortcut icon" href="/favicon.png">
    <title>Sign In</title>

    {{ HTML::style('packages/qlcorp/vext-security/css/bootstrap/bootstrap.min.css') }}
    {{ HTML::style('packages/qlcorp/vext-security/css/bootstrap/bootstrap-theme.min.css') }}
    {{ HTML::script('packages/qlcorp/vext-security/js/jquery.min.js') }}
    {{ HTML::script('packages/qlcorp/vext-security/js/bootstrap/bootstrap.min.js') }}
    {{ HTML::script('packages/qlcorp/vext-security/js/jquery.validate.min.js') }}

    <style>
        body {
            background-color: #F7F7F6;
            padding-top: 70px
        }

        label::after{
            content: ':'
        }

        #main {
            width: 800px;
        }
    </style>

    <script>
        $( document ).ready(function() {
            $('.modal').on('hidden.bs.modal', function (event) {
                $(event.target).find('.alert').alert('close');
            });

            $('#contactFormSubmit').click(function(event) {
                event.preventDefault();
                var form_data = $('#contactFormSubmit').serialize();

                $.post('contact_us', form_data, function() {
                    $('#contact').modal('hide');
                });
            });

            //Forgot Password Form
            $('#forgotForm').validate({
                submitHandler: function(form) {
                    var form_data = $form.serialize();

                    $.post('forgot_password', form_data, function(data) {
                        $('#forgot').modal('hide');
                    }).fail(function(jqxhr, status, error) {
                        var response = JSON.parse(jqxhr.responseText);
                        $('#forgotError').alert('close');

                        $('#forgotBody').prepend(
                            "<div id='forgotError' class='alert alert-danger alert-dismissable'>"
                                + response.error.message
                                + '</div>'
                        );
                    });
                }
            });

            $('#forgotFormSubmit').click(function(event) {
                $form = $('#forgotForm');
                $form.submit();

                /*event.preventDefault();
                var form_data = $form.serialize();

                $.post('forgot_password', form_data, function(data) {
                    $('#forgot').modal('hide');
                }).fail(function(jqxhr, status, error) {
                    var response = JSON.parse(jqxhr.responseText);
                    $('#forgotError').alert('close');

                    $('#forgotBody').prepend(
                        "<div id='forgotError' class='alert alert-danger alert-dismissable'>"
                            + response.error.message
                            + '</div>'
                    );
                });*/
            });

        });
    </script>
</head>

<body>
<!-- Navigation Bar -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">{{Config::get('app.name', 'Welcome')}}</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li>
                    <a href="#forgot" data-toggle="modal">
                        <span class="glyphicon glyphicon-flag"></span>
                        Forgot Password
                    </a>
                </li>
                <li class="divider-vertical"></li>
                <li>
                    <a href="#contact" data-toggle="modal">
                        <span class="glyphicon glyphicon-envelope"></span>
                        Contact Us
                    </a>
                </li>
                <li class="divider-vertical"></li>
            </ul>
        </div>

    </div>
</nav>
<!-- Navigation Ends -->

<!-- Main Container -->
<section>
    <div class="container" id="main">
        <div class="well">
            <legend>Sign In</legend>
            <?php if ( isset($error) ): ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>Error: </strong><?= $error ?>
                </div>
            <?php endif; ?>
            {{ Form::model(null, array(
                'url' => 'login/authenticate',
                'method' => 'post',
                'id' => 'loginForm',
                'role' => 'form'
            )) }}

            <div class="form-group">
            @if ( $field === 'email' )
                {{ Form::label('email', 'E-Mail Address') }}
                {{ Form::email('email', null, array(
                    'required',
                    'class' => 'form-control',
                    'placeholder' => 'E-Mail'
                )) }}
            @else
                {{ Form::label($field, $label) }}
                {{ Form::text($field, null, array(
                    'required',
                    'class' => 'form-control',
                    'placeholder' => $label
                )) }}
            @endif
            </div>

            <div class="form-group">
            {{ Form::label('password', 'Password') }}
            {{ Form::password('password', array(
                'required',
                'class' => 'form-control',
                'placeholder' => 'Password'
            )) }}
            </div>

            {{ Form::submit('Sign In', array('class' => 'btn btn-default')) }}

            {{ Form::close() }}

        </div>
    </div>
    <p class="text-center text-muted ">
        <span class="glyphicon glyphicon-copyright-mark"></span>
        Copyright 2015 - Quantum Logic Corp.
    </p>

</section>
<!-- Main Container Ends -->

<!-- Forgot Password Model Box -->
<div id="forgot" class="modal fade" style="display: none; ">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Forgot Password</h4>
            </div>
            <div class="modal-body" id="forgotBody">
                <p>Enter your email address, and a temporary password will be sent to you.</p>
                <form id="forgotForm" role="form" method="POST">
                    <div class="form-group">
                        <input id="email" name="email" type="email" class="form-control" placeholder="Email" required />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="forgotFormSubmit">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Contact Us Model Box -->
<div id="contact" class="modal fade" style="display: none; ">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Contact Us</h4>
            </div>
            <div class="modal-body">
                <form action='/contact_us' role="form" id="contactForm" method="POST">
                    <div class="form-group">
                        <input type='text' id="name" name="name" class="form-control" placeholder="Name" required />
                    </div>

                    <div class="form-group">
                        <input id="email" name="email" type="email" class="form-control" placeholder="Email" required />
                    </div>

                    <div class="form-group">
                        <textarea id="message" name="message" class="form-control" placeholder="Message" rows="5" required ></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="contactFormSubmit" form="contactForm">Submit</button>
            </div>
        </div>
    </div>
</div>

</body>

</html>

