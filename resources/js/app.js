import "./bootstrap";
import { createApp } from "vue";
import router from "./router";
import {
    create,
    NMessageProvider,
    NCheckbox,
    NCard,
    NForm,
    NFormItem,
    NPagination,
    NTable,
    NInput,
    NButton,
    NModal,
    NSelect,
    NSpace,
    NLoadingBarProvider,
} from "naive-ui";
const naive = create({
    components: [
        NMessageProvider,
        NCheckbox,
        NCard,
        NForm,
        NFormItem,
        NTable,
        NPagination,
        NButton,
        NInput,
        NModal,
        NSelect,
        NSpace,
        NLoadingBarProvider
    ],
});
const app = createApp({});
app.use(naive);
app.use(router);
app.mount("#app");
