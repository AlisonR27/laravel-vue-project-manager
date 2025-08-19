import { defineStore } from 'pinia'
import { User } from '@/types';
import { ref } from 'vue';

export const useUserStore = defineStore('user', () => {
    const currentUser = ref(null as null | User)

    const initialized = ref(false)

    // state
    const users = ref<User[]>([])

    // getters
    const totalUsers = ref(users.value.length)
    const findById = (id: number) => users.value.find(u => u.id === id)


    // actions
    function fetchUsers() {
        fetch('/users/all')
            .then((response) => response.json())
            .then((data) => {
                setUsers(data)
                initialized.value = true
            })
            .catch((exc) => {
                console.info(exc);
            });
    }

    function addUser(user: User) {
        users.value.push(user)
    }

    function removeUser(id: number) {
        users.value = users.value.filter(u => u.id !== id)
    }

    function setUsers(newUsers: User[]) {
        users.value = newUsers
    }

    function setUser(user: User) {
        currentUser.value = user
    }

    function getUsersExceptById(id: number) {
        return users.value.filter((user: User) => user.id != id)
    }

    function initialize() {
        if(!initialized.value) fetchUsers()
    }

    return {
        initialized,
        users,
        totalUsers,
        findById,
        fetchUsers,
        addUser,
        removeUser,
        setUsers,
        setUser,
        getUsersExceptById,
        initialize
    }
})
