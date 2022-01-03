<template>
    <div>
        <div class="ms_container">
            <div class="search_bar" >
                <!-- se ad ogni keyup o cambiamento del valore dell'imput aggiorno le cordinate di latitudine e longitudine da passare alo slug del vue router -->
                <input list="addresses" name="search" v-model="search" @keyup="fetchResults(search)" @change="fetchResults(search)" placeholder="Dove vuoi andare?">
                <!-- il ":to" ridireziona al componente advancedSearch passando lo slug 'search' come parametro dell'URI -->
                <div v-if="this.longitude!=undefined && this.latitude!=undefined">
                    <router-link :to="{ name: 'search', params:{slug:(this.query+'&dist=10'),location:search}}">
                        <button class="ms_btn">Cerca<i class="far fa-paper-plane"></i></button>
                    </router-link>
                </div>
                <div v-else>
                    <button @click="notValidLocation()" class="ms_btn">Cerca <i class="far fa-paper-plane"></i></button>
                </div>
                <datalist @input="getLocation()" id="addresses">
                    <option v-for="(element, index) in searchResults[0]" :key="index" :value="element.address.freeformAddress"></option>
                </datalist>
            </div>
        </div>  
        <div class="ms_container-fluid" > 
            <Hero :sponsored="sponsored" />
            <div class="ms_container container-cards">
                <div class="ms_row ms_align-items-center">
                    <Card v-for="(apartment, index) in apartments" :key="index" :apartment="apartment"/>
                </div>
            </div>
            <ul>
                <!-- creo i numeri delle pagine in base alla ricerca effettuata -->
                <li v-for="index in lastPage" :key="index" @click="getPage(index), changePage()">{{index}}</li>
            </ul>
        </div>
    </div>
</template>

<script>

import Card from '../components/Card.vue';
import Hero from '../components/Hero.vue';

export default {
    name: 'Home',

    components: {
        Card,
        Hero
    },

    data() {
        return {
            latitude:undefined,
            longitude:undefined,
            apartments: [],//salvo gli appartamenti
            searchResults: [],//risultati che ritornano dalla chiamata api di tomtom
            search: "",//stringa in cui salvo i valori contenuti nella input della località
            query: '',
            page: 1,//la pagina, come standard 1 prima che l'utente la modifichi
            lastPage: null,//viene assegnata alla richiesta axios degli appartamenti
            sponsored: []//appartamenti sponsorizzati? a cosa serve? dovrebbe essere una feature implementata nella card
        }
    },

    methods: {
        notValidLocation(){
            alert('insert a valid location');
        },

        // },
        //ogni volta che scrivo un carattere nella barra di ricerca chiamo l'api di tomtom per chiedere eventuali risultati
        fetchResults(search){
            this.latitude=undefined;
            this.longitude=undefined;
            if(search != '') {
                fetch('https://api.tomtom.com/search/2/geocode/'+ search +'.json?key=jXiFCoqvlFBNjmqBX4SuU1ehhUX1JF7t&language=it-IT')
                .then(response => response.json())
                    .then(data =>{
                        this.searchResults = [];
                        this.searchResults.push(data.results);
                        this.latitude=this.searchResults[0][0].position.lat;
                        this.longitude=this.searchResults[0][0].position.lon;
                        this.query='&lat='+this.latitude+'&lon='+this.longitude;
                    })
            }
        },

        //al click sulla pagina cambia la richiesta degli appartamenti paginati
        getPage(index) {
            this.page = index;
        },

        //al cambio pagina richiamo l'api impaginata
        changePage() {
                axios.get(`http://localhost:8000/api/apartments/?page=${this.page}`)
            .then(response => {
                this.apartments = response.data.data.data;
            })
            .catch(error => {
                console.log(error)
            })
        }
    },
        
    //apppena monta la pagina chiamo i dati degli appartamenti e salvo 3 appartamenti sponsorizzati?
    mounted () {
        window.scrollTo(0, 0);
   
        axios.get(`http://localhost:8000/api/apartments/?page=${this.page}`)
        .then(response => {
            this.lastPage = response.data.data.last_page;
            this.apartments = response.data.data.data;
        })
        .catch(error => {
            console.log(error)
        });
        
    }
}
</script>

<style lang="scss" scoped>
.search_bar{
    display: flex;
    margin: 3rem 0 2rem 0;
    justify-content: center;
    input{
        width: 100%;
        max-width: 25rem;
        border: none;
        border-radius:20px;
        padding: 10px 20px;
        margin-right:10px;
        font-family: 'Raleway', sans-serif;
        font-size: 17px;
        text-align: center;
        background-color: #ede7e3;
        &:focus{
            outline:none;
        }
    }
    .ms_btn{
        font-family: 'Raleway', sans-serif;
        font-size: 17px;
        background-color: #ede7e3;
        .far{
            margin-left:5px;
        }
    }

    .container-cards {
    padding: 25px 0px 0px 0px;
    }
    
}

ul {
    display: flex;
    list-style: none;
    width: 25%;
    margin: 1.5rem auto;
    justify-content: center;

    li {
        font-size: 1rem;
        padding: 0.8rem;
        &:hover {
            cursor: pointer;
            transform: scale(1.2);
        }
    }
}
</style>