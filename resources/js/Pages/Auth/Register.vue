<template>
    <section class="register">
        <div class="register-main">

            <h1 class="text-3xl text-white px-6">Sign-up</h1>

            <form @submit.prevent="register" autocomplete="off">
                <div class="form-control">
                    <input type="text" name="name" v-model="registerData.name" placeholder="Name">
                    <Transition name="fade">
                        <p v-if="registerData.errors.name">{{ registerData.errors.name }}</p>
                    </Transition>
                </div>
                <div class="form-control">
                    <input type="text" name="email" v-model="registerData.email" placeholder="E-mail">
                    <Transition name="fade">
                        <p v-if="registerData.errors.email">{{ registerData.errors.email }}</p>
                    </Transition>
                </div>
                <div class="form-control">
                    <input type="password" name="password" v-model="registerData.password" placeholder="Password">
                    <Transition name="fade">
                        <p v-if="registerData.errors.password">{{ registerData.errors.password }}</p>
                    </Transition>
                </div>
                <div class="form-control">
                    <input type="password" name="password_confirmation" v-model="registerData.password_confirmation" placeholder="Confirm Password">
                    <Transition name="fade">
                        <p v-if="registerData.errors.password_confirmation">{{ registerData.errors.password_confirmation }}</p>
                    </Transition>
                </div>
                <div class="form-control action">
                    <button type="submit" :disabled="registerData.processing" :class="registerData.processing && 'opacity-20 pointer-events-none'">register</button>
                    <small>Already have an account? <Link :href="route('login')">Click here</Link></small>
                </div>
            </form>
        </div>
    </section>
</template>

<script setup>
import { route } from 'ziggy-js'
import { useForm, Link } from '@inertiajs/vue3';

const registerData = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null
})

const register = () => {
    registerData.post(route('register.store'))
}

</script>

<style lang="scss" scoped>

.register {
    position: relative;
    width: 100dvw;
    height: 100dvh;
    background-image: url('../../../assets/register-bg.jpg');
    background-position: center center;
    background-size: cover;
    background-repeat: no-repeat;
    
    .register-main {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 30rem;
        padding: 1rem;
        position: absolute;
        top: 35%;
        right: 20%;
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
                    border-bottom: 3px solid lightseagreen;
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

</style>

