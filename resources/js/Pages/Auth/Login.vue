<template>
    <section class="login">
        <div class="login-main">

            <h1 class="text-3xl text-white px-6">Sign-in</h1>

            <form @submit.prevent="login" autocomplete="off">
                <div class="form-control">
                    <input type="text" name="email" v-model="loginData.email" placeholder="E-mail">
                    <Transition name="fade">
                        <p v-if="loginData.errors.email">{{ loginData.errors.email }}</p>
                    </Transition>
                </div>
                <div class="form-control">
                    <input type="password" name="password" v-model="loginData.password" placeholder="Password">
                    <Transition name="fade">
                        <p v-if="loginData.errors.password">{{ loginData.errors.password }}</p>
                    </Transition>
                </div>
                <div class="form-control action">
                    <button type="submit" :disabled="loginData.processing" :class="loginData.processing && 'opacity-20 pointer-events-none'">login</button>
                    <small>Don't have an account? <Link :href="route('register')">Click here</Link></small>
                </div>
            </form>
        </div>
    </section>
</template>

<script setup>
import { route } from 'ziggy-js'
import { useForm, Link } from '@inertiajs/vue3';

const loginData = useForm({
    email: null,
    password: null
})

const login = () => {
    loginData.post(route('login'))
}

</script>

<style lang="scss" scoped>

.login {
    position: relative;
    width: 100dvw;
    height: 100dvh;
    background-image: url('../../../assets/login-bg.jpg');
    background-position: center center;
    background-size: cover;
    background-repeat: no-repeat;
    
    .login-main {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 30rem;
        padding: 1rem;
        position: absolute;
        top: 25%;
        left: 10%;
        background-color: rgba($color: #000000, $alpha: 0.2);
        border: 1px solid #e9e9e9;
        box-shadow: 5px 5px 5px rgba($color: #000000, $alpha: 0.2);
        border-radius: 1.4rem;
        overflow: hidden;
        backdrop-filter: blur(1rem);

        form {
            display: flex;
            flex-direction: column;
            padding: 1rem;
        }

        .form-control {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;

            &.action {
                margin-top: 0.8rem;

                small {
                    margin-top: 0.3rem;
                    color: #e9e9e9;

                    a, a:visited {
                        color: bisque;
                    }
                }
            }

            &:not(:last-child) {
                margin-bottom: 20px;
            }

            input {
                position: relative;
                width: 100%;
                min-height: 40px;
                border-radius: 0.32rem;
                background-color: #e9e9e9;
                padding-left: 0.7rem;
                outline: none;
                border-bottom: 3px solid transparent;
                transition: border .2s ease-in;
                color: #313131;
                font-size: 0.95rem;

                &:focus {
                    border-bottom: 3px solid orangered;
                }
            }

            p {
                margin-top: 5px;
                padding: 0 1rem;
                color: #e9e9e9;
                background-color: orangered;
            }

            button {
                width: fit-content;
                padding: 0 40px;
                border-radius: 0.3rem;
                cursor: pointer;
                min-height: 30px;
                background-color: #e9e9e9;
                transition: transform .25s ease;
                font-size: 0.8rem;
                font-weight: 600;
                color: #313131;

                &:hover {
                    transform: translateY(-5px) scale(1.05);
                }

                &:active {
                    transform: scale(0.9);
                }
            }
        }
    }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

</style>

