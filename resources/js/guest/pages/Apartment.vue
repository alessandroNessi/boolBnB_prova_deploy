<template>
    <div class="ms_container">
        <div v-if="message != null" class="ms_message">
            {{message}}
            <span @click="close()">x</span>
        </div>
        <div class="ms_row title">
            <div class="ms_col-12">
                <h2>{{apartment.title}} </h2>
                <div class="city">
                    {{apartment.city}}
                </div>
                <div class="address">
                    {{apartment.address}} {{apartment.number}}
                </div> 
            </div>
        </div>
        <div class="ms_row">
            <div class="ms_col-12 ms_col-md-8">
                <div class="container">
                    <img class="img" :src="`http://localhost:8000/storage/${apartment.cover}`" alt="">
                </div>
            </div>
            <div class="ms_col-12 ms_col-md-4">
                <div class="container">
                    <div id="map"></div>              
                </div>
            </div>
        </div>
        <div class="ms_row features">
            <div class="ms_col-12">
                <h3>Dettagli dell'appartamento</h3>
                <ul>
                    <li>
                        <i class="fas fa-campground"></i>
                        <span>Numero di stanze: <h4> {{apartment.rooms}}</h4></span>
                    </li>
                    <li>
                        <i class="fas fa-user-astronaut"></i>
                        <span>Ospiti: <h4> {{apartment.guests_number}}</h4></span>
                    </li>
                    <li>
                        <i class="fas fa-sink"></i>
                        <span>Numero di bagni: <h4> {{apartment.bathrooms}}</h4></span>
                    </li>
                    <li>
                        <i class="fas fa-ruler"></i>
                        <span>Mq: <h4> {{apartment.sqm}}</h4></span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="ms_row gallery">
            <div class="ms_col-12 ms_col-md-8">
                <h3>Altre immagini</h3>
                <div v-if="pics.length > 0" class="pics">
                    <img v-for="(element,index) in pics" :key="index" :src="`http://localhost:8000/storage/${element.url}`" alt="">
                </div>
                <div v-else>
                    Nella galleria non sono presenti immagini.
                </div>
            </div>
            
        </div>
        <div class="ms_row services">
            <div class="ms_col-12">
                <h3>Servizi disponibili</h3>
                <ul v-if="services.length > 0" class="service_list">
                    <li v-for="(element,index) in services" :key="index">
                        <a href="#">{{element}}</a>
                    </li>
                </ul>
                <div v-else>
                    Nessun servizio associato a questo appartamento.
                </div>
            </div>
        </div>
        <div class="ms_row description">
            <div class="ms_col-12 ms_col-md-8">
                <h3>Descrizione</h3>
                {{apartment.description}}
            </div>
        </div>
        <div class="ms_row form">
            <div class="ms_col-12 ms_col-md-8">
                <h3>Scrivi a questo host</h3>
                <form :action="`http://localhost:8000/messages/store/${apartment.id}`" method="POST">
                    <input type="hidden" name="_token" :value="csrf">
                    <input type="text" name="name" placeholder="Scrivi il tuo nome">
                    <input type="email" name="email" :value="email != null ? email : ''" placeholder="Inserisci la tua mail" required>
                    <textarea name="content" cols="30" rows="10" placeholder="Scrivi il messaggio" required></textarea>
                    <div class="container-button">
                        <button type="submit" class="ms_btn">Invia messaggio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template> 

<script>

import tt from '@tomtom-international/web-sdk-maps';

export default {
    name: 'Apartment',

    data() {
		return {
			apartment: [],
            services: [],
            pics: [],
            lat: null,
            lon: null,
            map: null,
            marker: null,
            email: null,
            csrf: document.querySelector('meta[name="csrf-token"]').content,
            message: null
		}
    },

    methods: {
        close() {
            this.message = null
        }
    },

    mounted(){
            axios.get(`/api/apartments/${this.$route.params.slug}`)
            .then(response=>{
                this.apartment = response.data.data;
                this.lat = this.apartment.latitude;
                this.lon = this.apartment.longitude;
    
                this.map = tt.map({
                        key: 'lXA4qKasPyxqJxup4ikKlTFOL3Z89cp4',
                        container: 'map',
                        zoom: 15,
                        center: [this.lon, this.lat]
                    });
                this.map.addControl(new tt.NavigationControl);
                var markerHeight = 35, markerRadius = 10, linearOffset = 25;
                var popupOffsets = {
                    'top': [0, 0],
                    'top-left': [0,0],
                    'top-right': [0,0],
                    'bottom': [0, -markerHeight],
                    'bottom-left': [linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
                    'bottom-right': [-linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
                    'left': [markerRadius, (markerHeight - markerRadius) * -1],
                    'right': [-markerRadius, (markerHeight - markerRadius) * -1]
                };
                var popup = new tt.Popup({offset: popupOffsets, className: 'popup-frame'})
                .setHTML(`<p class="popup">${this.apartment.title}</p>`);
                this.marker = new tt.Marker({
                    color: '#ffa628',
                    width: '27',
                    height: '35',
                }).setLngLat([this.lon, this.lat]).setPopup(popup).addTo(this.map);
                // console.log(this.apartment);
                // console.log(this.marker);
                this.apartment.services.forEach(id => {
                    axios.get(`/api/services/${id}`)
                    .then(response =>{
                    console.log(response.data);
                    this.services.push(response.data.data.name);
                    })
                });

                axios.get(`/api/pics/${this.$route.params.slug}`)
                .then(response =>{
                    console.log(response.data.data);
                    for (const key in response.data.data) {
                        this.pics.push(response.data.data[key])
                    }
                    // this.pics = response.data.data;
                })

                fetch('https://api.ipify.org?format=json')
                    .then(x => x.json())
                    .then(({ ip }) => {
                    console.log(ip);
                    axios.post(`/api/statistics/${this.apartment.id}/${ip}`)
                    .then(function (response) {
                    console.log(response);
                    })
                    .catch(function (error) {
                    console.log(error);
                    });
                });
            
            })

        if(window.Laravel.isLoggedin) {
        this.email = window.Laravel.user.email;
        };
        window.scrollTo(0, 0);

        if(window.Redirect){
            this.message = window.Redirect.success;
            console.log(window.Redirect.success);
            window.Redirect.success = null;
            console.log(window.Redirect.success)
        }
    },
}
</script>

<style lang="scss" scoped>

    @import '../../../sass/front';

    .title, .container, .features, .services, .description, .form, .gallery {
        padding: 20px ;
    }

    h2{
        padding: 15px 0;
        font-size: 30px;
        text-shadow:-2px 2px #113950;
        font-family: 'Poppins', sans-serif; 
        text-transform: capitalize;
    }

    .city, .address {
        text-transform: capitalize;
        font-size:18px;
    }
    
    .city{
        font-weight: bold;
        color:$orange;
        font-size:21px;
        margin-top:15px;
    }
    
    .address{
        color:$lightblue;
        margin-top:8px;
    }

    .img {
        display: block;
        object-fit: cover;
        object-position: center;
    }

    .img, #map {
        border-radius: 7px;
        width: 100%;
        height: 400px;
        .popup{
            color: black !important;
        }
    }

    h3{
        color:$orange;
        font-size:20px;
        margin-bottom: 18px;
    }

    .features{
        margin-top: 10px;
        ul {
            list-style: none;
            li{
                margin:8px 0;
            }
            span {
                font-size: 17px;
                font-weight:500;
                
                h4{
                    display:inline;
                    margin-left:4px;
                    color:$beige;
                    text-shadow:-1px 2px $darker;
                }
            }
            .fas {
                margin-right: 9px;
                color:$lightblue;
                font-size:18px;
            }
        }
    }

    .service_list {
        list-style: none;
        li {
            display: inline;
            a { 
                transition: 1s;
                margin: 5px 0px;
                display: inline-block;   
                color: #ede7e3;
                font-weight:500;
                margin-right: 10px;
                background-color: $mediumblue;
                color:$beige;
                padding:7px 12px;
                border-radius:15px;
                min-width:80px;
                text-align: center;
                &:hover {
                    background-color: $darker;
                }
            }
        } 
    }

    .form {
        input {
            display: block;
            width:100%;
            margin: 25px 0;
            padding:12px 15px;
            border-radius: 20px;
            border:none;
            background-color:$beige;
            font-family: 'Raleway', sans-serif;
            font-size:15px;
            &:focus {
                outline: none;
            }
        }
        textarea{
            width:100%;
            padding: 15px;
            border-radius: 15px;
            border:none;
            background-color: $beige;
            resize: none;
            font-family: 'Raleway', sans-serif;
            font-size:15px;
            &:focus {
                outline: none;
            }
        }  
    }

    .ms_btn{
        transition: 1s;
        margin: 30px 0 70px 0;
        padding:12px 15px;
        border-radius: 20px;
        border: none;
        background-color: $lightblue;
        font-family: 'Raleway', sans-serif;
        font-size: 15px;
        color: white;
        font-weight: 600;
        &:hover{
            box-shadow: 1px 1px 2px #0d424d81;
            cursor : pointer;
            background-color: $mediumblue;
        }
    }
   
   .ms_message {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 1.5rem;
        background-color: $beige;
        border: 2px solid $lightblue;
        border-radius: 1.3rem;
        color: #0d4f75;
        font-weight: bold;

        span {
            font-size: 1.3rem;

            &:hover {
                cursor: pointer;
            }
        }
   }



    .pics {
        display: flex;
        flex-wrap: wrap;
    }

    .pics img {
        display: block;
        margin: 5px;
        width: 150px;
        height: 100px;
        object-fit: cover;
        object-position: center;
        border-radius:5px;
    }

    @media screen and (max-width: 768px) {
        .container-button {
            width: 100%;
            display: flex;
            justify-content: center;
        }
    }
</style>