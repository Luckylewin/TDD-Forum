<template>
    <div>
        <form v-if="canUpdate" method="post" enctype="multipart/form-data">
            <image-upload class="mr-1" @loaded="onLoad"></image-upload>
        </form>

        <img :src="avatar" alt="avatar" width="200" height="200">

    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue';

    export default {
        name: "avatar-form",

        components: {ImageUpload},

        props: ['user'],

        data() {
            return {
                avatar:"/storage/" + this.user.avatar_path,
            }
        },

        created() {

        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            }
        },

        methods: {
            onLoad(avatar) {

                this.avatar = avatar.src;

                this.upload(avatar.file);
            },

            upload(avatar) {
                let data = new FormData();

                data.append('avatar', avatar);

                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => flash(':) Avatar uploaded'))
            }
        }
    }
</script>

<style scoped>

</style>