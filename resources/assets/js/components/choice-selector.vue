<template>
  <div>
    <p class="mb-2">{{ question }}</p>
    <ul>
      <li v-for="choice in choices" v-bind:key="choice.name">
        <label>
          <input
            type="radio"
            v-bind:name="choice.name"
            v-bind:checked="selectedChoice === choice.name"
            v-on:change="selectedChoice = choice.name"
          >
          {{ choice.label }}
        </label>
      </li>
    </ul>
    <div v-for="choice in choices" v-bind:key="choice.name" v-show="selectedChoice === choice.name">
      <slot v-bind:name="choice.name"/>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    question: { type: String },
    choices: { type: Array, required: true },
  },
  data() {
    return {
      selectedChoice: this.choices[0].name,
    };
  },
};
</script>
