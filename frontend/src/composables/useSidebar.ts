import { ref } from 'vue'

const activeDropdown = ref<string>('')

export function useSidebar() {

  const toggle = (name: string) => {
    activeDropdown.value = activeDropdown.value === name ? '' : name
  }

  return {
    activeDropdown,
    toggle
  }
}