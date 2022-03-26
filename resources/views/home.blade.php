<!DOCTYPE html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css"
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> --}}
    <style>
        .header-title {
            text-align: center;
            padding: 10px;
            margin: 0 auto;
        }

        .inner-section , .result-section{
            text-align: center;
            border: 1px solid black;
            border-radius: 20px;
            padding: 15px;
            margin-top: 15px;
        }

        .options {
            display: flex;
            flex-direction: row;
            justify-content: center;
            width: 100%;
        }

        input[type="radio"] {
            margin: 0 10px 0 10px;
        }

    </style>
</head>

<body>

    <div class="container wrapper">
        <div class="panel panel-primary" style="margin-top:15px">
            <p class="header-title">Hello, Lets Play </p>
        </div>
        <div class="inner-section">
            <form action="#">
                @csrf
                <div class="random-card-value">
                    <p> Random card value is {{ $random_card_value }} </p>
                </div>

                <input type="hidden" class="card-value" value="{{ $random_card_value }}">
                <div class="options">
                    <input type="radio" name="card_guess" class="card_guess" id="higher" value="1">
                    <label for="higher">Higher</label><br>
                    <input type="radio" name="card_guess" class="card_guess" id="lower" value="0">
                    <label for="lower">Lower</label><br>
                </div>
                <input type="submit" value="Submit" class="btn btn-primary" id="submit">
            </form>
        </div>
        <div class="result-section">
            <h4>Your Stats </h4>
            <div class="win_stat">
                Number of wins: {{ $win_stat }}
            </div>
            <div class="loss_stat">
                Number of loses: {{ $loss_stat }}
            </div>
        </div>
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
                                `Correct,the new random card value is ${response.data.new_card_value}`

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
