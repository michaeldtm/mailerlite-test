<template>
  <form @submit.prevent="submitSubscriber">
    <div class="space-y-4">
      <div class="grid grid-cols-6 gap-6">
        <div class="col-span-6 sm:col-span-6 lg:col-span-2">
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <input type="text" name="name" id="name" v-model="form.name"
                 class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div class="col-span-6 sm:col-span-6 lg:col-span-2">
          <label for="email_address" class="block text-sm font-medium text-gray-700">E-mail address</label>
          <input type="email" name="email_address" id="email_address" v-model="form.email_address"
                 class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div class="col-span-6 sm:col-span-6 lg:col-span-2">
          <label for="state" class="block text-sm font-medium text-gray-700">State</label>
          <select v-model="form.state" id="state" name="state"
                  class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            <option v-for="(state, key) in options.states" :key="key" :value="state.id">{{ state.text }}</option>
          </select>
        </div>
      </div>
      <div class="flex justify-end">
        <button type="submit"
                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          Save
        </button>
      </div>
    </div>
  </form>
  <div class="mt-8 flex flex-col">
    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
          <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Title</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
              <th scope="col" class="relative py-3.5 pl-3 pr-4 text-right space-x-4 text-sm font-medium sm:pr-6">
                <button v-show="!isEditing" @click="newField" class="text-indigo-600 hover:text-indigo-900">
                  New field
                </button>
              </th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-for="(field, key) in form.fields" :key="key">
              <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                <input v-if="field.editing" type="input" name="title" id="title" v-model="editingField.title"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <span v-else>{{ field.title }}</span>
              </td>
              <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                <select v-if="field.editing" v-model="editingField.type" id="type" name="type"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                  <option v-for="(type, key) in options.types" :key="key" :value="type.id">{{ type.text }}</option>
                </select>
                <span v-else>{{ field.type }}</span>
              </td>
              <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right space-x-4 text-sm font-medium sm:pr-6">
                <button v-show="!isEditing" @click="editField(field)" class="text-indigo-600 hover:text-indigo-900">
                  Edit
                </button>
                <button v-show="field.editing" @click="saveField(field)" class="text-indigo-600 hover:text-indigo-900">
                  Save
                </button>
                <button v-show="field.editing" @click="cancelEditing(field)" class="text-red-600 hover:text-red-900">
                  Cancel
                </button>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SubscriberEdit",
  data: () => ({
    form: {
      email_address: '',
      name: '',
      state: ''
    },
    editingField: {
      title: '',
      type: '',
    },
    options: {
      states: [
        {id: 'active', text: 'Active'},
        {id: 'unsubscribed', text: 'Unsubscribed'},
        {id: 'junk', text: 'Junk'},
        {id: 'bounced', text: 'Bounced'},
        {id: 'unconfirmed', text: 'Unconfirmed'},
      ],
      types: [
        {id: 'string', text: 'String'},
        {id: 'date', text: 'Date'},
        {id: 'number', text: 'Number'},
        {id: 'boolean', text: 'Boolean'},
      ]
    }
  }),
  mounted() {
    axios.get(`/api/subscribers/${this.$route.params.id}`)
      .then(({data}) => this.form = data.data)
  },
  methods: {
    submitSubscriber() {
      axios.put(`/api/subscribers/${this.$route.params.id}`, this.form)
    },
    newField() {
      this.form.fields.push({
        title: '',
        type: '',
        editing: true
      })
    },
    editField(field) {
      const index = this.form.fields.findIndex(f => f.id === field.id)
      const selected = this.form.fields[index]
      this.form.fields.splice(index, 1, {...selected, editing: true})
      this.editingField = selected
    },
    saveField(field) {
      const method = field.id ? 'PUT' : 'POST'
      const url = !field.id ? '/api/fields' : `/api/fields/${field.id}`
      axios({method, url, data: {...this.editingField, subscriber_id: this.form.id}})
        .then(({data: res}) => {
          const id = res.data.id
          const index = this.form.fields.findIndex(f => f.id === id)
          if (index >= 0) {
            this.form.fields.splice(index, 1, {...res.data, editing: false})
          } else {
            this.form.fields.push({...res.data, editing: false})
          }
        })
        .finally(() => {
          this.editingField = {title: '', type: '', editing: false}
        })
    },
    cancelEditing(field) {
      if (field.id) {
        const index = this.form.fields.findIndex(f => f.id === field.id)
        const selected = this.form.fields[index]
        this.form.fields.splice(index, 1, {...selected, editing: false})
      } else {
        this.form.fields.pop()
      }
    }
  },
  computed: {
    isEditing() {
      return this.form.fields?.some(field => field.editing)
    }
  }
}
</script>
