<template>
  <RouterView />
</template>

<script setup>

</script>

<style>
    :root {
    --max-width: 1200px;
    --gap: 1rem;
    --bg: #0f172a;
    --panel: #111827;
    --text: #e5e7eb;
    --muted: #9ca3af;
    --accent: #22c55e;
    --border: #1f2937;
    }
    * { box-sizing: border-box; }
    html, body, #app { height: 100%; margin: 0; background: var(--bg); color: var(--text); }
    a { color: inherit; text-decoration: none; }
    .container { max-width: var(--max-width); margin: 0 auto; padding: 1rem; }
    .panel { background: var(--panel); border: 1px solid var(--border); border-radius: .5rem; }
    input, select, button {
    background: #0b1220; color: var(--text); border: 1px solid var(--border);
    border-radius: .4rem; padding: .6rem .7rem;
    }
    button { cursor: pointer; }
    button.primary { background: var(--accent); color: #052e16; border-color: transparent; }
    label { font-size: .9rem; color: var(--muted); }
</style>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
