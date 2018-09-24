<template>
    <div id="container">
        <div id="left-wrapper">
            <div id="logo">
                <img src="spd.png" alt="logo">
            </div>
            <div id="middle-img">
                <img src="3.png" alt="description">
            </div>
            <div id="small-description">
                <p>
                    Southern Property Developers is an fully functional company to support entrepreneurs in Sri Lanka
                </p>
            </div>
            <div id="nav-footer">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Support</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
                <p>The system is under development, sorry for the inconvenience.</p>
                <p>Bro-15 Team</p>
            </div>
        </div>
        <div id="right-wrapper">
            <div id="login_header">
                <p id="header-txt">Login</p>
            </div>
            <div id="login_body">
                <div id="login_form">
                    <form>
                        <div id="input_email">
                            <input name="email" id="email" type="text" v-on:input="hideLabelEmail" v-model="email"/>
                            <label for="email" v-if="seenEmail">Email</label>
                        </div>
                        <div id="input_password">
                            <input id="password" name="password" type="password" v-on:input="hideLabelPassword" v-model="password"/>
                            <label for="password" v-if="seenPassword">Password</label>
                        </div>
                        <input type="submit" id="submit" value="login" v-on:click="onSubmitForm"/>
                    </form>
                </div>
            </div>
            <div id="footer_wrapper">
                <p id="footer_text">Southern Property Developers. All Rights Reserved | <a href="#">Privacy Policy</a></p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted(){
            console.log("component mounted")
        },
        data() {
            return {
                seenEmail: true,
                seenPassword: true,
                email: "",
                password: ""
            }
        },
        methods: {
            hideLabelEmail: function (){
                if (this.email === ''){
                    return this.seenEmail = true;
                }
                return this.seenEmail = false;
            },
            hideLabelPassword: function () {
                if (this.password === ''){
                    return this.seenPassword = true;
                }
                return this.seenPassword = false;
            },
            onSubmitForm: function (event){
                if (this.email === ''){
                    //show error msg
                    event.preventDefault();
                    this.$notify({
                        group: 'auth',
                        title: 'Error',
                        type: 'error',
                        text: 'Please enter Email and Password',
                    });
                }else if(this.password === ''){
                    //show error msg
                }else{
                    //email and password entered send request if email is okay and password is wrong display alert
                    //if password is wrong display error msg
                }
            }
        }
    }
</script>

<style>
    #container {
        overflow: hidden;
        background-color: #eaf5ea;
    }
    #left-wrapper {
        width: 70%;
        height: 100%;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        overflow-x: hidden;
        overflow-y: hidden;
    }
    #right-wrapper {
        width: 30%;
        height: 100%;
        position: fixed;
        z-index: 1;
        top: 0;
        right: 0;
        background-color: #16b1c7;
        overflow-x: hidden;
        overflow-y: hidden;
    }
    #logo {
        position: relative;
        margin-left: 20px;
        margin-top: 20px;
        display: inline-block;
        text-align: center;
    }
    #logo img {
        width: 60px;
        height: 60px;
    }
    #middle-img {
        position: relative;
        margin-top: 20px;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }

    #middle-img img {
        width: 450px;
        height: 300px;
    }

    #small-description {
        position: relative;
        margin-top: 30px;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }

    #small-description p {
        color: #0f0f0f;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";

    }

    #nav-footer {
        position: relative;
        margin: 30px auto 20px;
    }

    #nav-footer ul {
        text-align: center;
    }

    #nav-footer ul li {
        padding-left: 15px !important;
        padding-right: 10px !important;
        display: inline-block;
    }

    #nav-footer ul li a {
        color: #6e786e;
        text-transform: uppercase;
        text-decoration: none;
    }

    #nav-footer p {
        color: #ff6761;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        text-align: center;
    }

    #login_header{
        position: relative;
        margin-left: auto;
        margin-right: auto;
        margin-top: 20px;
        text-align: center;
        z-index: 1;
    }

    #header-txt {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        font-size: 30px;
        color: #ffffff;
        text-transform: uppercase;
        font-weight: bold;
    }

    #login_body {
        position: relative;
        margin-top: 20px;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
        z-index: 1;
        overflow: hidden;
    }

    #login_form {
        position: inherit;
    }

    input {
        width: 70%;
        padding: 10px 0;
        border: none;
        margin: 0 auto 30px;
        box-sizing: border-box;
        box-shadow: none;
        border-bottom: 1px solid #eaf5ea;
        background-color: #16b1c7;
    }

    input:hover,
    input:focus,
    input:active:focus{
        color: #e7f7ff;
        outline:none;
        background-color: #16b1c7;
        border-bottom:1px solid #000000;
    }

    input[type="submit"]{
        border-bottom: none;
        background: #ff6761;
        color: #eaf5ea;
        cursor: pointer;
        text-transform: uppercase;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
    }

    input[type="submit"]:hover,
    input[type="submit"]:focus,
    input[type="submit"]:focus {
        border-bottom: none;
    }


    #input_email,
    #input_password {
        position: relative;
    }

    #input_email label,
    #input_password label {
        position: absolute;
        top: 10px;
        left: 15%;
        transition: .5s;
        pointer-events: none;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
    }

    input:hover ~ label{
        margin-top: -12px;
        font-size: 10px;
        color: #eaf5ea;
        font-weight: bold;
    }

    #footer_wrapper {
        position: absolute;
        left: 5%;
        bottom: 20px;
        height: 20px;
        text-align: center;
    }

    #footer_text {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        font-size: 12px;
        color: #e7f7ff;
        font-weight: bold;
    }
    #footer_text a {
        text-decoration: none;
        color: #e7f7ff;
        cursor: pointer;
    }
</style>
