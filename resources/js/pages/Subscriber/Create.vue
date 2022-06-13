<template>
  <form @submit.prevent="submit">
    <div class="space-y-4">
      <div class="grid grid-cols-6 gap-6">
        <div class="col-span-6 sm:col-span-6 lg:col-span-2">
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <input type="text" name="name" id="name" v-model="form.name"
                 class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
          <span class="text-sm text-red-600 font-medium pb-0.5">{{this.errors.form?.name[0]}}</span>
        </div>
        <div class="col-span-6 sm:col-span-6 lg:col-span-2">
          <label for="email_address" class="block text-sm font-medium text-gray-700">E-mail address</label>
          <input type="email" name="email_address" id="email_address" v-model="form.email_address"
                 class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
          <span class="text-sm text-red-600 font-medium pb-0.5">{{this.errors.form?.email_address[0]}}</span>
        </div>
        <div class="col-span-6 sm:col-span-6 lg:col-span-2">
          <label for="state" class="block text-sm font-medium text-gray-700">State</label>
          <select v-model="form.state" id="state" name="state" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            <option v-for="(state, key) in options.states" :key="key" :value="state.id">{{ state.text }}</option>
          </select>
          <span class="text-sm text-red-600 font-medium pb-0.5">{{this.errors.form?.state[0]}}</span>
        </div>
      </div>
      <div class="flex justify-end">
        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          Save
        </button>
      </div>
    </div>
  </form>
</template>

<script>
export default {
  name: "SubscriberCreate",
  data: () => ({
    form: {
      email_address: '',
      name: '',
      state: ''
    },
    errors: {
      form: null
    },
    options: {
      states: [
        {id: 'active', text: 'Active'},
        {id: 'unsubscribed', text: 'Unsubscribed'},
        {id: 'junk', text: 'Junk'},
        {id: 'bounced', text: 'Bounced'},
        {id: 'unconfirmed', text: 'Unconfirmed'},
      ]
    }
  }),
  methods: {
    submit() {
      axios.post('/api/subscribers', this.form)
        .then(({data: res}) => this.$router.push({name: 'SubscriberEdit', params: {id: res.data.id}}))
        .catch(({response: res}) => {
          this.errors.form = {...res.data.errors}
        })
    }
  }
}
</script>
