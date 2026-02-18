import { ref, inject } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import * as yup from 'yup'
import { useValidation } from './useValidation'
import { useToast } from './useToast'

export default function usePosts() {
    const { errors, validate, clearErrors, hasError, getError } = useValidation()
    const router = useRouter() //this was to connect to the routes file i think
    const toast = useToast() //this was the utility to make those small message windows easily (like "user successfully saved!" type message windows)

    const isLoading = ref(false) //a variable that we need to change manually to indicate if an async is still loading or not
    const posts = ref([]) //a posts array, so on the api calls we can save multiple posts if needed
    const initialPost = { //a base instance of the post object
        title: '',
        content: '',
        categories: [],
        thumbnail: null
    }
    const post = ref({ ...initialPost }) //a post object with predefined values so that we just have to edit the attributes and not make a whole new post object
    const validationErrors = errors //the errors gotten on the validation of the object


    const postSchema = yup.object({ //a validator for posts objects using the "yup" library. the validator rule names are pretty self explainatory, but if needed, probs not very difficult to search new ones
        title: yup.string().trim().required('El título es obligatorio'),
        content: yup.string().trim().required('El contenido es obligatorio'),
        categories: yup.array().nullable(),
        thumbnail: yup.mixed().nullable(),
    })

    const getPosts = async () => { //EXAMPLE OF An ASYNC GET FUNCTION USING AXIOS
        return axios.get('/api/posts') //we do axios.get as our usual fetch, and in parentesis we put the api route
            .then(response => { //response is whatever the server returned
                posts.value = response.data; //if we receive an array of posts, we can set it into the posts array that we defined already for later use
                //now that im looking, we are returning the response, yet we are putting the response array data into the posts variable to... maybe ask later 
                return response; //return the response we get from the server to wherever this function was called
            })
    }

    const getPost = async (id) => {
        return axios.get('/api/posts/' + id)
            .then(response => {
                post.value = response.data.data;
                return response;
            })
    }

    const storePost = async (post) => {// EXAMPLE OF AN ASYNC POST FUNCTION!!
        if (isLoading.value) return; //check if we called it before and are waiting for a response. isLoading is set by us inside this function

        isLoading.value = true //as said, we set isloading to true so this function cant be ran more than once
        clearErrors() //idk, ask if really interested

        const { isValid } = validate(postSchema, post) // we validate if the post object we are sending to the server follows the rules of our postSchema 
        if (!isValid) { //if the validation goes wrong, we stop the api call and make it so its no longer loading
            isLoading.value = false
            return
        }

        const serializedPost = serializePost(post) //serialize the post object into an array, this serialization also controls arrays inside of arrays.

        axios.post('/api/posts', serializedPost, { // we do a post call to the /api/posts endpoint, sending our post (now as an array)
            headers: {
                "content-type": "multipart/form-data" //don't know, ask if needed?
            }
        })
            .then(response => {//if there weren't any errors we get the response
                //router.push({ name: 'posts.index' })
                toast.crud.created('Post') //with toast, we display a small message
            })
            .catch(error => { //with "catch" in case an error is returned by the server (error is know if its not code 200) we grab this error and can manage it
                if (error.response?.data) { //the "error" parameter we got is also just whatever the server sent as a response (i think).
                    validationErrors.value = error.response.data.errors //idk what this is doing
                }
            })
            .finally(() => isLoading.value = false) //after we finished with the call (after the response or errors aswell) we set isloading to false, so the call can be executed again.
    }

    const resetPost = () => {
        post.value = { ...initialPost }
        clearErrors()
    }

    const updatePost = async (post) => { //Shouldn't be too different from a store
        if (isLoading.value) return;

        isLoading.value = true
        clearErrors()

        const { isValid } = validate(postSchema, post)
        if (!isValid) {
            isLoading.value = false
            return
        }

        axios.put('/api/posts/' + post.id, post)
            .then(response => {
                router.push({ name: 'posts.index' })
                toast.crud.updated('Post')
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => isLoading.value = false)
    }

    const deletePost = async (id) => {
        axios.delete('/api/posts/' + id)
            .then(response => {
                getPosts()
                toast.crud.deleted('Post')
            })
            .catch(error => {
                toast.error('Error', 'No se pudo eliminar el post')
            })
    }

    const serializePost = (data) => {
        const form = new FormData()
        Object.entries(data).forEach(([key, value]) => {
            if (value === undefined || value === null) return
            if (Array.isArray(value)) {
                value.forEach(item => form.append(`${key}[]`, item))
            } else {
                form.append(key, value)
            }
        })
        return form
    }
    return {
        posts,
        post,
        getPosts,
        getPost,
        storePost,
        updatePost,
        deletePost,
        resetPost,
        hasError,
        getError,
        validationErrors,
        isLoading
    }
}
