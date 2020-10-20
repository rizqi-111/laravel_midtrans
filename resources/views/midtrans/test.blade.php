<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Tes Payment</title>
    </head>
    <body>
        <div id="example">
            <button v-on:click="handlePayButton">BaYaR</button>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.5.1/vue-resource.js" integrity="sha512-GZgi6mLZHE1kxwBwvhZV0OayASqAwhdFIy/5T4wOxw1WaThaSEIe7ppG5WmJLwbolvMqYrqVmu2eUfm+5HNumA==" crossorigin="anonymous"></script>
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-bMG5ezNlDHrhA1i0"></script>
        <script type="text/javascript">
            var vm = new Vue({
                el : '#example',
                data : function(){
                    return {
                        data_midtrans : {
                            'transaction_details' : {
                                'order_id' : "order-6123893447993712",
                                'gross_amount' : 44000
                            },
                            'customer_details' : {
                                'first_name'    : 'Regi',
                                'last_name'     : 'anugrah',
                                'email'         : 'regi@regi.com',
                                'phone'         : '0234567890'
                            }
                        }
                    }
                },
                methods: {
                    handlePayButton : function (event) {
                        this.$http.post('/api/generate',{
                            data : this.data_midtrans
                        })
                        .then(response => {
                            //console.log(response.data.data.token)
                            snap.pay(response.data.data.token)
                        }, response => {
                            console.log('error : ' + response)
                        })
                    }
                }
            })
        </script>
    </body>
</html>