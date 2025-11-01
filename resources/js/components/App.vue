<template>
    <div id="app">
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: 'App'
}
</script>

<style>
:root {
    --primary-color: #5E56E7;
    --secondary-color: #F8F7FF;
    --grey-light: #F0F0F6;
    --grey-medium: #A0A0A0;
    --grey-dark: #333333;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Montserrat', sans-serif;
    background-color: var(--secondary-color);
    color: var(--grey-dark);
    line-height: 1.6;
}

.heading-1 {
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
    font-size: 48px;
    line-height: 1.2;
}

.heading-2 {
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
    font-size: 30px;
    line-height: 1.2;
}

.genre-card {
    font-family: 'Montserrat', sans-serif;
    font-weight: 400;
    font-size: 20px;
}

.body-text {
    font-family: 'Montserrat', sans-serif;
    font-weight: 400;
    font-size: 16px;
}

.search-box {
    font-family: 'Montserrat', sans-serif;
    font-weight: 400;
    font-size: 16px;
}

.book-name {
    font-family: 'Montserrat', sans-serif;
    font-weight: 400;
    font-size: 12px;
}

.book-author {
    font-family: 'Montserrat', sans-serif;
    font-weight: 400;
    font-size: 12px;
}


.text-center {
    text-align: center;
}

.min-vh-100 {
    min-height: 100vh;
}

.visually-hidden {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    white-space: nowrap !important;
    border: 0 !important;
}

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>