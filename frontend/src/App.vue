<script setup lang="ts">
import { RouterView, useRoute } from 'vue-router'
import Toast from './components/Toast.vue'
import { useToast } from './composables/useToast';
import { watch } from 'vue';
import { useGlobalModal } from './composables/useGlobalModal';
const route = useRoute();
const { hideToast } = useToast();
const { isVisible, config, closeGlobalModal } = useGlobalModal();

watch(() => route.fullPath, () => {
  hideToast();
});

const handleAccept = async (data: any) => {
  const currentConfig = config.value;
  if (currentConfig?.onAccept) {
    try {
      await currentConfig.onAccept(data);
      closeGlobalModal();
    } catch (error) {
      console.error("Error en onAccept del modal global:", error);
    }
  }
};
</script>

<template>
  <RouterView />
  <Toast />

  <Teleport to="body">
    <component 
      :is="config.component" 
      v-if="isVisible && config" 
      :key="config.header" 
      v-bind="config.props" 
      :show="isVisible"
      :header="config.header" 
      @close="closeGlobalModal" 
      @accept="handleAccept" 
    />
  </Teleport>
</template>

<style scoped>
/*
header {
  line-height: 1.5;
  max-height: 100vh;
}
.logo {
  display: block;
  margin: 0 auto 2rem;
}

nav {
  width: 100%;
  font-size: 12px;
  text-align: center;
  margin-top: 2rem;
}

nav a.router-link-exact-active {
  color: var(--color-text);
}

nav a.router-link-exact-active:hover {
  background-color: transparent;
}

nav a {
  display: inline-block;
  padding: 0 1rem;
  border-left: 1px solid var(--color-border);
}

nav a:first-of-type {
  border: 0;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }

  nav {
    text-align: left;
    margin-left: -1rem;
    font-size: 1rem;

    padding: 1rem 0;
    margin-top: 1rem;
  }
}
*/
</style>
