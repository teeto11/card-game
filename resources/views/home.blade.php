<!DOCTYPE html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" 
    integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>

<body>
    <div class="wrapper">
        <div>
            <p>Hello, Lets Play </p>
        </div>

        <form action="#">
            @csrf
            <div class="random-card-value">
                <p> Random card is {{ $random_card_value }} </p>
            </div>
            <div class="win_stat">
                Number of wins: {{ $win_stat }}
            </div>
            <div class="loss_stat">
                Number of loses: {{ $loss_stat }}
            </div>
            <input type="hidden" class="card-value" value="{{ $random_card_value }}">
            <div class="options">
                <input type="radio" name="card_guess" class="card_guess" id="higher" value="1">
                <label for="html">Higher</label><br>
                <input type="radio" name="card_guess" class="card_guess" id="lower" value="0">
                <label for="css">Lower</label><br>
            </div>
            <input type="submit" value="Submit" id="submit">
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js"
        integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $('#submit').click(function(e) {
                e.preventDefault()
                let input = $('.card_guess:checked').val()
                let card_value = $('.card-value').val()
                console.log(input, card_value)
                $.ajax({
                    url: `/submit-guess`,
                    type: "post",
                    data: {
                        input: input,
                        card_value: card_value,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log(response);

                        $(".random-card-value").empty()
                        $(".win_stat").empty()
                        $(".loss_stat").empty()
                        let randomCardDiv = ''
                        if (response.data.correct_counter > 0 && response.data.wins < 5) {
                            randomCardDiv =
                                `Correct,the new random card number ${response.data.new_card_value}`

                        } else if (response.data.correct_counter > 0 && response.data.wins ===
                            5) {
                            $(".options").hide()
                            $("#submit").hide()
                            randomCardDiv =
                                `You have won the card game, <a href="{{ url('/') }}">Start Again</a>`

                        } else if (response.data.incorrect_counter > 0) {
                            $(".options").hide()
                            $("#submit").hide()
                            randomCardDiv =
                                `Game over, start again, <a href="{{ url('/') }}">Start Again</a>`

                        }

                        let winStatDiv = `Number of wins: ${ response.data.wins }`
                        let lossStatDiv = `Number of loses: ${ response.data.loses }`

                        $(".random-card-value").append(randomCardDiv)
                        $(".card-value").val(`${response.data.new_card_value}`)
                        $(".win_stat").append(winStatDiv)
                        $(".loss_stat").append(lossStatDiv)

                    }
                })
            })
        })
    </script>
</body>

</html>
