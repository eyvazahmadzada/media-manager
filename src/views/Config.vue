<template>
  <div class="config">
    <header>
      <h1>RULES:</h1>
      <p>Upload an image: Press on the SPACE key</p>
      <p>Create a rectangle: Click on the screen</p>
      <p>Resize an item: Use resizers</p>
    </header>
    
    <main>
      <form class="config-form" @submit="submitForm">
        <h2>Connect to MySQL</h2>

        <!-- Dynamically creating form controls -->
        <div v-for="control in formControls" :key="control.label">
          <div class="config-form-control">
            <label>{{control.label}}</label>
            <input 
              :type="control.type" 
              :placeholder="control.placeholder"
              v-model="control.value"
            >
            <small>{{control.error}}</small>
          </div>
        </div>

        <button class="config-form-button" type="submit">Connect</button>

      </form>
    </main>
  </div>
</template>

<script>
import API from '../config/api';

export default {
  name: "Config",
  data: function() {
    return {
      formControls: [
        { label: "Host", value: "", type: "text", placeholder: "localhost", error: "", required: true },
        { label: "Username", value: "", type: "text", placeholder: "root", error: "", required: true },
        { label: "Password", value: "", type: "password", placeholder: "", error: "" }
      ]
    }
  },
  methods: {
    submitForm: function(e) {
      let isFormValid = true;

      // Check if all the fields are filled
      this.formControls.forEach(control => {
        if(control.required && control.value.trim() === "") {
          isFormValid = false;
          control.error = `${control.label} is required`;
        } else {
          control.error = "";
        }
      });

      if(isFormValid) {
        const host = this.formControls[0].value;
        const username = this.formControls[1].value;
        const password = this.formControls[2].value;

        // Connect to MySQL
        API.connect({ host, username, password })
          .then(res => {
            if(!res.data.error) {
              // Send connection info to parent
              console.log(res.data);
              setTimeout(() => {
                this.$emit("onConnect", true);
              }, 1500);
            } else {
              console.log(res.data.error);
            }
          });
      }
      
      e.preventDefault();
    }
  }
};
</script>

<!-- Styling with sass -->
<style lang="scss" scoped>
  .config {
    header {
      background-color: #eaeaea;
      text-align: center;
      padding: 20px 0;
    }

    &-form {
      width: 50%;
      margin: 20px auto;
      padding: 20px;
      text-align: center;
      background-color: #748EC4;
      color: white;
      border-radius: 10px;

      h2 { margin-top: 0; }

      &-control {
        width: 50%;
        margin: 16px auto;
        text-align: left;

        label {
          display: block;
          margin-bottom: 5px;
        }

        input {
          width: 100%;
          outline: none;
          padding: 10px;
          border: none;
          border-radius: 5px;
        }

        small {
          color: red;
          margin-top: 3px;
        }
      }

      &-button {
        background-color: #E4B645;
        color: white;
        font-weight: bold;
        text-transform: uppercase;
        width: 200px;
        padding: 15px 20px;
        border: none;
        border-radius: 5px;
        outline: none;
        cursor: pointer;
        transition: 0.5s ease-out;

        &:hover {
          filter: brightness(90%);
        }
      }
    }
  }
</style>
