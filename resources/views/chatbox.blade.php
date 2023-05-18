<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<link rel="stylesheet" href="{{ asset('assets/floating-btn.css') }}">
<div class="floatingButtonWrap">
    <div class="floatingButtonInner">
        <a href="javascript:;" class="floatingButton">
            <i class="fas fa-comments icon-default"></i>
        </a>
        <div class="floatingChatBox">
            @include('welcome')
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('.floatingButton').on('click',
            function(e) {
                e.preventDefault();
                $(this).toggleClass('open');
                if ($(this).children('.fa').hasClass('fa-comments')) {
                    $(this).children('.fa').removeClass('fa-comments');
                    $(this).children('.fa').addClass('fa-close');
                } else if ($(this).children('.fa').hasClass('fa-close')) {
                    $(this).children('.fa').removeClass('fa-close');
                    $(this).children('.fa').addClass('fa-comments');
                }
                $('.floatingChatBox').stop().slideToggle();
            }
        );
        $(this).on('click', function(e) {

            var container = $(".floatingButton");
            // if the target of the click isn't the container nor a descendant of the container
            if (container.is(e.target)) {
                if (container.hasClass('open')) {
                    container.removeClass('open');
                }
                if (container.children('.fa').hasClass('fa-close')) {
                    container.children('.fa').removeClass('fa-close');
                    container.children('.fa').addClass('fa-comments');
                }
                $('.floatingChatBox').hide();
            }
        });
    });
</script>
