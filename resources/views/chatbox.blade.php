<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<style>
    .floatingButtonWrap {
        display: block;
        position: fixed;
        bottom: 45px;
        right: 45px;
        z-index: 999999999;
    }

    .floatingButtonInner {
        position: absolute;
    }

    .floatingButton {
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        width: 60px;
        height: 60px;
        text-align: center;
        background: -webkit-linear-gradient(45deg, #8769a9, #507cb3);
        background: -o-linear-gradient(45deg, #8769a9, #507cb3);
        background: linear-gradient(45deg, #8769a9, #507cb3);
        color: #fff;
        line-height: 50px;
        position: absolute;
        border-radius: 50% 50%;
        bottom: 0px;
        right: 0px;
        border: 5px solid #b2bedc;
        opacity: 1;
        transition: all 0.4s;
    }

    .floatingButton .fas {
        font-size: 25px !important;
    }

    .floatingButton.open,
    .floatingButton:hover,
    .floatingButton:focus,
    .floatingButton:active {
        opacity: 1;
        color: #fff;
    }


    .floatingButton .fa {
        transform: rotate(0deg);
        transition: all 0.4s;
    }

    .floatingButton.open .fa {
        transform: rotate(270deg);
    }

    .floatingChatBox {
        position: absolute;
        bottom: 70px;
        right: 0px;
        width: 400px;
        height: auto;
        display: none;
        background-color: white;
        border-radius: 10px;
        border: 1px solid #d5d5d5;
        overflow: hidden;
    }

    /* width */
    ::-webkit-scrollbar {
        width: 5px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
<div class="floatingButtonWrap">
    <div class="floatingButtonInner">
        <a href="javascript:;" class="floatingButton">
            <i class="fas fa-comments icon-default"></i>
        </a>
        <div class="floatingChatBox shadow" id="section-to-update">
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

        function updateSection() {
            // Make an AJAX request to fetch the updated content
            fetch('{{ route('chat-box') }}')
                .then(response => response.text())
                .then(data => {
                    // Update the content of the section with the fetched data
                    document.getElementById('section-to-update').innerHTML = data;
                })
                .catch(error => {
                    console.log('Error:', error);
                });
        }

        // Call the updateSection function at regular intervals (e.g., every 5 seconds)
        // setInterval(updateSection, 20000);

        $("#section-to-update").load("{{ route('chat-box') }}");
    });
</script>
