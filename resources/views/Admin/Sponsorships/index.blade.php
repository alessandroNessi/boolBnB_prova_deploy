@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="content">
                    <form method="post" id="payment-form" action="{{route("admin.sponsorships.process", ['apartment_id'=> $apartment->id, 'sponsorship_id' => $sponsorship->id])}}">
                        @csrf
                        <section>
                            <h2>{{$sponsorship->title}}</h2>
                            <h4 class="ms_lightblue">Durata: {{$sponsorship->duration}} ore dalla ricezione del pagamento</h4>
                            <label for="amount">
                              <span class="input-label ms_lightblue">Amount</span>
                            </label>
                            <div class="input-wrapper amount-wrapper mb-4">
                                <input id="amount" name="amount" type="tel" min="1" placeholder="Amount" value="{{$sponsorship->price}}" readonly>
                            </div>

                            {{-- componente con le opzioni della transazione importato da js --}}
                            <div class="bt-drop-in-wrapper">
                                <div id="bt-dropin"></div>
                            </div>

                        </section>

                        <input id="nonce" name="payment_method_nonce" type="hidden" />
                        <button class="ms-button mt-4 button" type="submit"><span>Confirm Transaction</span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    {{-- importo il componente del pagamento --}}
    <script src="https://js.braintreegateway.com/web/dropin/1.13.0/js/dropin.min.js"></script>


    <script>
        var form = document.querySelector('#payment-form');
        var client_token = "{{ $token }}";
        braintree.dropin.create({
          authorization: client_token,
          selector: '#bt-dropin',
          paypal: {
            flow: 'vault'
          }
        }, function (createErr, instance) {
          if (createErr) {
            console.log('Create Error', createErr);
            return;
          }
          // intercettazione del submit del pagamento ed esecuzione del controllo transazione
          form.addEventListener('submit', function (event) {
            event.preventDefault();
            instance.requestPaymentMethod(function (err, payload) {
              if (err) {
                console.log('Request Payment Method Error', err);
                return;
              }
              document.querySelector('#nonce').value = payload.nonce;
              //se il pagamento va a buon fine eseguo il submit della form
              form.submit();
            });
          });
        });
    </script>
@endsection