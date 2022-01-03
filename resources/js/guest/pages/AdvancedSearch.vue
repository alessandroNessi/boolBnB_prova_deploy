<template>
    <div>
        <div class="searchbar">
            <div class="input_bar">

                <!--DATALIST keyup non funziona -->
                <input list="addresses" id="addressInput" style="width:500px" name="search" v-model="search" @keyup="fetchResults(search)" @change="fetchResults(search)" placeholder="Dove vuoi andare?">
                <datalist style="width:500px" id="addresses">
                    <option style="width:500px" v-for="(element, index) in searchResults[0]" :key="index" :value="element.address.freeformAddress"></option>
                </datalist>

                <label for="rooms">Stanze</label>
                <input @change="mountSlug()" type="number" min="1" max="255" placeholder="1" v-model.number="rooms" id="rooms">

                <label for="guests">Ospiti</label>
                <input @change="mountSlug()" type="number" min="1" max="255" placeholder="1" v-model.number="guests" id="guests">

                <label for="distance">Distanza</label>
                <input @change="mountSlug()" type="number" min="0" placeholder="10 km" v-model.number="distance" id="distance">
            </div>
            
            <div class="service_row">
                <div class="services_container" v-for="(service, index) in services" :key="index">
                    <button class="ms_btn_services" @click="addService(service.id),mountSlug()" :class="serviceFilter.includes(service.id) ? 'active' : ''">{{service.name}}</button>
                </div>
                <!-- riscrivo la query ogni qualvolta clicco su mostra appartamenti -->
                <router-link id="routerlink" :to="{ name: 'search', params: { slug:query, location:search} }">
                    <button id="button" @click="getPage(1),getApartments()" class="ms_btn_advance">Filtra appartamenti </button>
                </router-link>
            </div>
            
        </div>
        <div class="ms_container">
            <div class="ms_col-12 ms_col-md-4">
                <div class="container">
                    <div id="map"></div>              
                </div>
            </div>
        </div>
        <div class="container-cards ms_container">
            <h1 v-if="noResults">Non ci sono appartamenti che corrispondono alle tue richieste</h1>
            <div v-else class="ms_row ms_align-items-center">
                <Card v-for="(apartment, index) in apartments" :key="index" :apartment="apartment"/>
            </div>
            <ul>
                <li v-for="index in lastPage" :key="index" @click="getPage(index)">{{index}}</li>
            </ul>
        </div>
    </div>
</template>

<script>
import Card from '../components/Card.vue';
import tt from '@tomtom-international/web-sdk-maps';

export default {
    name: 'AdvancedSearch',

    components: {
        Card
    },


    data() {
        return {
            apartments : [],
            marker:null,
            lastPage : null,
            // start:true, //no longer used
            searchResults: [],
            rooms: 1,
            guests: 1,
            map: null,
            originalMap: null,
            distance: null,
            lastDistance: null,
            lat: null,
            lon: null,
            search: this.$route.params.location,
            // lastSearch: "", //no longer used
            services: [],//services asked by query
            query:'',//query for requests
            serviceFilter : [],//actual services selected
            noResults : false,//used fot the message 'no apartments'
            page: 1
        }
    },

    methods: {

        //compilazione automatica della datalist su chiamata tomtom e aggiornamento query
        fetchResults(search) {
            this.lat=undefined;
            this.lon=undefined;
            if(this.search!='') {
                fetch('https://api.tomtom.com/search/2/geocode/'+ search +'.json?key=jXiFCoqvlFBNjmqBX4SuU1ehhUX1JF7t&language=it-IT')
                .then(response => response.json())
                .then(data =>{
                    this.searchResults = [];
                    this.searchResults.push(data.results);
                    this.lat=this.searchResults[0][0].position.lat;
                    this.lon=this.searchResults[0][0].position.lon;
                    this.mountSlug();
                })
            }
        },

        //makes the services toggle works
        addService(id) {
            if(this.serviceFilter.includes(id)) {
                let index = this.serviceFilter.indexOf(id);
                this.serviceFilter.splice(index, 1)
            } else {
                this.serviceFilter.push(id)
            }
        },

        //get the page selected by clicking on the page number, add it to the query and refresh apartments pagination
        getPage(index) {
            this.page = index;
            this.mountSlug();
            this.getApartments();
        },

        //exoplode te slug received to renew the data values so if a address it's passed it can recover all the information required
        explodeSlug(){
            let slug=this.$route.params.slug;
            slug=slug.substr(1);
            let exploded=slug.split('&');
            exploded.forEach(element => {
                element=element.split('=');
                switch (element[0]) {
                    case 'lon':
                        this.lon=parseFloat(element[1]);
                        this.page=1;
                    break;
                    case 'lat':
                        this.lat=parseFloat(element[1]);
                    break;
                    case 'dist':
                        this.distance=parseInt(element[1]);
                    break;
                    case 'rooms':
                        this.rooms=parseInt(element[1]);
                    break;
                    case 'guests':
                        this.guests=parseInt(element[1]);
                    break;
                    case 'services':
                        this.serviceFilter=[];
                        let services=element[1].split('-');
                        services.forEach(service=>{
                            this.serviceFilter.push(parseInt(service));
                        });
                    break;
                }
            });
        },

        //mounts the query
        mountSlug(){
            let query='';
            // let changedServices=false;
            if(this.lat!=undefined){
                query+='&lat='+this.lat;
                // this.changeMapCenter();
            }
            if(this.lon!=undefined){
                query+='&lon='+this.lon;
                // this.changeMapCenter();
            }
            if(this.distance>0){
                query+='&dist='+this.distance;
                // this.changeMapZoom();
            }
            if(this.rooms>0){
                query+='&rooms='+this.rooms;
            }
            if(this.guests>0){
                query+='&guests='+this.guests;
            }
            if(this.serviceFilter.length>0){
                query+='&services='+this.serviceFilter.join('-');
                // changedServices=true;
                // this.page=1;
            }
            query+='/?page='+this.page;
            this.query=query;
            this.$route.params.slug=query;
            // if(changedServices){
            //     this.getApartments();
            // }
        },

        //get apartments function
        getApartments(){
            if(this.distance!=this.lastDistance){
                this.generateNewMap();
            }
            axios.get(`http://localhost:8000/api/apartments/search/${this.query}`)
            .then(response => {
                this.apartments = [];
                this.apartments = response.data.data.data;
                this.noResults = false;
                if(this.apartments.length < 1){
                    this.noResults = true;
                }
                this.lastPage = response.data.data.last_page;
                this.changeMapCenter();
                this.changeMapZoom();
                this.setApartmentsPOI();
            })
            .catch(error => {
                console.log(error);
            })
            window.scrollTo(0, 0);
        },

        //se apartaments as Point Of Intrests on the map and 
        //adding mouse eneter and leave for popup
        //adding mouseclick for route to the selected apartment
        setApartmentsPOI(){
            // let centralMapMarker = new tt.Marker({
            //         color: '#ff0000',
            //         width: '22',
            //         height: '27'
            //     }).setLngLat([this.lon, this.lat]).addTo(this.map);
            this.apartments.forEach((apartment,index)=>{
                console.log(apartment);
                let markerHeight = 35, markerRadius = 10, linearOffset = 25;
                let popupOffsets = {
                    'top': [0, 0],
                    'top-left': [0,0],
                    'top-right': [0,0],
                    'bottom': [0, -markerHeight],
                    'bottom-left': [linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
                    'bottom-right': [-linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
                    'left': [markerRadius, (markerHeight - markerRadius) * -1],
                    'right': [-markerRadius, (markerHeight - markerRadius) * -1]
                };
                let popup = new tt.Popup({offset: popupOffsets, className: apartment.slug}).setLngLat([apartment.longitude, apartment.latitude]).setHTML(`
                    <div style="color:black">
                        <h4 >${apartment.title}</h4>
                        <p>Distanza:    ${apartment.distance.toFixed(1)} km</p>
                        <p>Posti letto: ${apartment.guests_number}</p>
                        <p>Stanze:      ${apartment.rooms}</p>
                    </div>
                `);
                let marker = new tt.Marker({
                    color: '#ffa628',
                    width: '27',
                    height: '35'
                }).setLngLat([apartment.longitude, apartment.latitude]).setPopup(popup).addTo(this.map);
                //adding events
                document.getElementsByClassName('mapboxgl-marker-anchor-bottom')[index].addEventListener("mouseenter", (e) => {this.showPopup(this.map,marker)});
                document.getElementsByClassName('mapboxgl-marker-anchor-bottom')[index].addEventListener("mouseleave", (e) => {this.hidePopup(this.map,marker)});
                document.getElementsByClassName('mapboxgl-marker-anchor-bottom')[index].addEventListener("click", (e) => {this.myFunction(apartment.slug)});
            });
        },

        showPopup(map,marker){
            marker.getPopup().addTo(map);
        },

        hidePopup(map,marker){
            marker.getPopup().remove(map);
        },

        myFunction(slug){
            this.$router.push({ name: 'apartment', params: { slug: slug } });
        },

        generateNewMap(){
            this.lastDistance=this.distance;
            this.map='';
            this.map = tt.map({
                key: 'lXA4qKasPyxqJxup4ikKlTFOL3Z89cp4',
                container: 'map',
            });
            this.originalMap=this.map;
            this.map.addControl(new tt.NavigationControl); 
        },

        changeMapCenter(){
            this.map.setCenter([this.lon, this.lat]);
        },

        changeMapZoom(){
            //156543= max meters per pixel, 350 map height
            this.map.setZoom(Math.ceil(Math.log2((156.543*350)/this.distance))-2);
        }
    },

    mounted(){
        this.search=this.$route.params.location;
        this.query=this.$route.params.slug;
        this.explodeSlug();
        axios.get(`/api/services`)
            .then(response =>{
            this.services = response.data.data;
        });
        this.generateNewMap();
        this.originalMap=this.map;
        this.map.addControl(new tt.NavigationControl);
        this.getApartments();
    }
}
</script>

<style lang="scss" scoped>
.d-none{
    display: none;
}
#map{
    height: 350px;
    border-radius: 15px;
}
.searchbar {
    // display: flex;
    justify-content: center;
    align-items: center;
    height: 130px;
    font-size: 15px;
    font-weight: 500;
    border-bottom: 1px solid #0a333b9a;
    width: 90%;
    margin:0 auto;
    .input_bar{
        display:flex;
        width:100%;
        justify-content: center;
        flex-wrap: nowrap;
        margin-bottom: 0.9rem;
        line-height:50px;
        font-size:17px;
    }

    input {
        width: 125px;
        padding: 0.5rem ;
        text-align: center;
        border: none;
        border-radius: 1rem;
        font-family: 'Raleway', sans-serif;
        margin: 0.5rem 0.8rem 0.5rem 0.3rem;
        font-size: 16px;
        background-color: #ede7e3;
        &:focus{
                outline:none;
            }
    }
}
.service_row{
    display: flex;
    width:80%;
    margin:0 auto;
    justify-content: center;
    align-items: center;
}
.services_container {
    width:100%;
    .ms_btn_services {
        min-width: 120px;
        padding: 0.6rem 0.3rem;
        margin: 0.5rem 0.2rem;
        background-color: #82c0cc;
        color: white;
        border: none;
        border-radius: 1rem;
        font-family: 'Raleway', sans-serif;
        &:hover{
            cursor:pointer;
            background-color: #489fb5;
            color:white;
            
        }
        &.active {
            background-color: #ffa628;
            font-weight: bold;
            color:white;
            box-shadow: 1px 1px 2px rgba(150, 147, 147, 0.603);
        }
    }
}

.container-cards {
        margin-top:30px;
        padding: 25px 0px 0px 0px;

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
    }

.ms_btn_advance{
        min-width: 190px;
        padding: 0.6rem 1.2rem;
        border-radius: 1rem;
        background-color: rgb(26, 21, 21);
        color: white;
        font-family: 'Raleway', sans-serif;
        border: none;
        margin-left:1.5rem;
        justify-content: center;
        align-items: center;
        font-size: 15px;
        &:hover{
            cursor:pointer;
            background-color: white;
            color:black;
            box-shadow: 1px 1px 2px rgba(150, 147, 147, 0.603);
            transform:scale(1.02);
        }
    }

</style>