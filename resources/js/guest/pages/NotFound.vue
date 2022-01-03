<template>
    <div class="page">
        <div class="number">404</div>
        <h1>PAGE NOT FOUND</h1>
    </div>
</template>

<script>

</script>

<style lang="scss" scoped>

    .page {
        color: white;
        background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('../images/page_not_found.jpg');
        background-size: cover;
        background-position: center;
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        .number {
            font-size: 64px;
        }
    }

</style>