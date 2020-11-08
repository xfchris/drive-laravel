

@if ( (count($errors->all()) > 0) ||
		Session::get('success') ||
		Session::get('error') ||
		Session::get('warning') ||
		Session::get('info') )
    <div class="container notificaciones">
        <div class="row">

            @if (count($errors->all()) > 0)
                <div class="alert alert-dismissable alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <ul>{!! implode('', $errors->all('<li class="error">:message</li>')) !!}</ul>
                </div>
            @endif

            @if ($message = Session::get('success'))
                <div class="alert alert-dismissable alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>

                    <span class="glyphicon glyphicon-thumbs-up"></span>
                    {!! $message !!}
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-dismissable alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {!! $message !!}
                </div>
            @endif

            @if ($message = Session::get('warning'))
                <div class="alert alert-dismissable alert-warning">

                    <span class="glyphicon glyphicon-exclamation-sign"></span>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {!! $message !!}
                </div>
            @endif

            @if ($message = Session::get('info'))
                <div class="alert alert-dismissable alert-info">

                    <span class="glyphicon glyphicon-info-sign"></span>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {!! $message !!}
                    <?php session()->forget('info'); ?>
                </div>
            @endif
        </div>
    </div>

@endif
