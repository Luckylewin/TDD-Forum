<template>
    <ul class="pagination" v-if="shouldPaginate">
        <li v-show="prevUrl">
            <a href="#" aria-label="Previous" rel="prev" @click.prevent="page--">
                <span aria-hidden="true">« Previous</span>
            </a>
        </li>

        <li v-show="nextUrl">
            <a href="#" aria-label="Next" rel="next" @click.prevent="page++">
                <span aria-hidden="true">Next »</span>
            </a>
        </li>
    </ul>
</template>

<script>
    export default {
        name: "paginator",

        props: ['dataSet'],

        data() {
            return {
                page:1,
                prevUrl:'',
                nextUrl:''
            }
        },

        // 侦听器 监控 dataSet 属性
        watch: {
            dataSet() {
                this.page = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
            },

            page() {
                this.broadcast().updateUrl();
            }
        },

        computed: {
            // 为true 时才显示分页区域
            shouldPaginate() {
                return !! this.prevUrl || !! this.nextUrl;
            }
        },

        methods: {
            broadcast() {
                // 绑定 changed 事件 以便让父组件监听到 进行翻页相关的动作
                return this.$emit('changed', this.page);
            },

            updateUrl() {
                // 将 page 参数发送给父组件
                history.pushState(null, null, '?page=' + this.page);
            }
        }
    }
</script>

<style scoped>

</style>