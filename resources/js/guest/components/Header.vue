<template>
    <header :class="[scrollPosition > 20 ? 'ms_bg_white' : '']">
        <div  class="header ms_container-fluid">
            <div class="left">
                <router-link :to="{ name: 'home' }">
                    <img :src="'/'+'images/logo_small.png'" alt="logo">
                </router-link>
            </div>
            <nav class="right">
                <ul>
                    <li>
                        <a  class="register" :class="[scrollPosition > 20 ? 'register_scrolled' : '']" href="/register">Diventa host</a>
                    </li>
                    <li>
                        <a class="user" :class="[scrollPosition > 20 ? 'user_scrolled' : '']" href="/login"><i class="fas fa-user-circle"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
</template>

<script>
export default {
	name: "HeaderLeo",
    data(){
        return {
            scrollPosition: ''
        }
    },
    methods: {
        updateScroll() {
                this.scrollPosition = window.scrollY
            }
    },
    mounted() {
        window.addEventListener('scroll', this.updateScroll);
    }
}
</script>

<style lang="scss" scoped>
    
header {
    transition: 1s;
    z-index: 10;
}

.header {
    position: fixed;
    width: 100%;
    background: rgb(72,159,181);
    background: linear-gradient(180deg, rgba(72, 159, 181, 0.623) 3%, rgba(22, 105, 122, 0) 100%);
    left: 0px;
    top: 0px;
    
    padding: 5px 17px;
    display: flex;
    align-items: center;
    .left, .right {
        width: 50%;
    }

    .left {
        img {
            width: 92px;
            margin-left: 20px;
        }
    }
    .right {
        ul {
            display: flex;
            justify-content: right;
            align-items: center;
            list-style: none;
            li {
                margin-left: 15px;
                
                .user {
                    transition: 1s;
                    color: #ede7e3;
                    font-size: 27px;
                    margin-right:18px;
                    &:hover {
                        color: #ffa628;
                        transition: 0.2s;
                        
                    }
                }
                .register {
                    transition: 1s;
                    font-size: 17px;
                    color: #ede7e3;
                    font-weight: 600;
                    &:hover {
                        transition: 0.3s;
                        font-weight: 600;
                        color: #ffa628;
                    }
                }
            }
        }
    }
}

.ms_bg_white {
    transition: 1s;
    background-color: #82c0cc;
    .right {
        .register_scrolled, .user_scrolled {
            transition: 1s;
            color: #16697a !important;
            &:hover{
                color:#ffa628 !important;
            }
        }
    }
}


</style>