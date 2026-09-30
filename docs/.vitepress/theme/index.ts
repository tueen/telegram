import DefaultTheme from 'vitepress/theme'
import Mermaid from './components/Mermaid.vue'
import LlmSkillCopy from './components/LlmSkillCopy.vue'
import ApiCard from './components/ApiCard.vue'
import ApiGroup from './components/ApiGroup.vue'
import './custom.css'

export default {
  extends: DefaultTheme,
  enhanceApp({ app }: { app: any }) {
    app.component('Mermaid', Mermaid)
    app.component('LlmSkillCopy', LlmSkillCopy)
    app.component('ApiCard', ApiCard)
    app.component('ApiGroup', ApiGroup)
  },
}
