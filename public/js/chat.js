// public/js/chat.js

function chatApp(myId) {
    return {
        myId: myId,
        showChat: false,
        selectedUserId: null,
        selectedUserName: '',
        selectedUserAvatar: '',
        selectedUserUsername: '',
        showOptionsMenu: false,
        showMessageSearch: false,   // Chat ichidagi qidiruvni ko'rsatish
        messageSearchQuery: '',     // Chat ichidan qidirilayotgan so'z
        messages: [],
        newMessage: '',
        searchQuery: '',
        globalUsers: [],
        isLoading: false,
        isSending: false,
        editingMessageId: null,

        init() {
            // Xavfsizlik uchun CSRF tokenni o'rnatamiz (Layoutdagi meta tagdan olinadi)
            let token = document.querySelector('meta[name="csrf-token"]');
            if(token) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
            }
            
            // Har 4 soniyada fonda xabarlarni tekshiramiz
            setInterval(() => {
                if (this.selectedUserId && !this.editingMessageId) {
                    this.fetchMessages(false);
                }
            }, 4000);
        },

        // Foydalanuvchini Global qidirish
        searchGlobal() {
            let q = this.searchQuery.trim();
            if (q.length > 1) {
                axios.get(`/chat/search?q=${q}`)
                    .then(response => { this.globalUsers = response.data; })
                    .catch(error => console.error("Qidiruvda xatolik:", error));
            } else {
                this.globalUsers = [];
            }
        },

        // Odam tanlanganda
        selectUser(id, name, avatar, username) {
            this.selectedUserId = id;
            this.selectedUserName = name;
            this.selectedUserAvatar = avatar;
            this.selectedUserUsername = username;
            
            this.showChat = true;
            this.showOptionsMenu = false;
            this.showMessageSearch = false; // Chat izlashni yopamiz
            this.messageSearchQuery = '';   // Chat izlash so'zini tozalaymiz
            this.messages = [];
            this.editingMessageId = null;
            this.newMessage = '';
            
            this.searchQuery = '';
            this.globalUsers = [];
            
            this.fetchMessages(true);
        },

        closeChat() {
            this.showChat = false;
            this.selectedUserId = null;
            this.editingMessageId = null;
            this.showOptionsMenu = false;
            this.showMessageSearch = false;
        },

        fetchMessages(showLoader = true) {
            if (showLoader) this.isLoading = true;
            axios.get(`/chat/${this.selectedUserId}/messages`)
                .then(response => {
                    let isNewMsg = this.messages.length !== response.data.length;
                    this.messages = response.data;
                    if (showLoader || isNewMsg) this.scrollToBottom();
                })
                .finally(() => { this.isLoading = false; });
        },

        sendMessage() {
            if (this.newMessage.trim() === '') return;
            
            this.isSending = true;
            let msgContent = this.newMessage;
            
            if (this.editingMessageId) {
                // Tahrirlash
                axios.patch(`/chat/message/${this.editingMessageId}`, { message: msgContent })
                    .then(response => {
                        let msg = this.messages.find(m => m.id === this.editingMessageId);
                        if(msg) msg.message = msgContent;
                        this.cancelEdit();
                    })
                    .catch(error => { alert("Xatolik yuz berdi."); })
                    .finally(() => { this.isSending = false; });
            } else {
                // Yangi yuborish
                this.newMessage = ''; 
                this.messages.push({
                    id: Date.now(), sender_id: this.myId, message: msgContent, created_at: new Date().toISOString(), is_read: false
                });
                this.scrollToBottom();

                axios.post(`/chat/${this.selectedUserId}/send`, { message: msgContent })
                    .then(response => { this.fetchMessages(false); })
                    .finally(() => { this.isSending = false; });
            }
        },

        editMessage(msg) {
            this.editingMessageId = msg.id;
            this.newMessage = msg.message;
            setTimeout(() => { document.querySelector('input[x-model="newMessage"]').focus(); }, 50);
        },

        cancelEdit() {
            this.editingMessageId = null;
            this.newMessage = '';
        },

        deleteMessage(msgId) {
            if(!confirm("Ushbu xabarni haqiqatan ham o'chirmoqchimisiz?")) return;
            axios.delete(`/chat/message/${msgId}`)
                .then(() => { this.messages = this.messages.filter(m => m.id !== msgId); });
        },

        clearHistory() {
            if(!confirm("Suhbat tarixini tozalashni xohlaysizmi?")) return;
            this.showOptionsMenu = false;
            axios.delete(`/chat/${this.selectedUserId}/clear`)
                .then(() => { this.messages = []; });
        },

        deleteChat() {
            if(!confirm("Chatni butunlay o'chirmoqchimisiz?")) return;
            this.showOptionsMenu = false;
            axios.delete(`/chat/${this.selectedUserId}/clear`)
                .then(() => { window.location.reload(); });
        },

        scrollToBottom() {
            setTimeout(() => {
                const chatBox = document.getElementById('chat-messages');
                if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
            }, 100);
        },

        formatTime(datetime) {
            if (!datetime) return '';
            const date = new Date(datetime);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    }
}

// Kompyuter ekrani uchun yon panelni tortish (Resizer) logikasi
document.addEventListener('DOMContentLoaded', function () {
    const resizer = document.getElementById('resizer');
    const sidebar = document.getElementById('sidebar');
    let isResizing = false;

    if(resizer && sidebar && window.innerWidth > 1024) {
        resizer.addEventListener('mousedown', function (e) {
            isResizing = true;
            document.body.classList.add('no-select');
            document.body.style.cursor = 'col-resize';
        });

        document.addEventListener('mousemove', function (e) {
            if (!isResizing) return;
            const containerOffset = sidebar.parentElement.getBoundingClientRect().left;
            let newWidth = e.clientX - containerOffset;
            if (newWidth < 280) newWidth = 280;
            if (newWidth > window.innerWidth / 2) newWidth = window.innerWidth / 2;
            sidebar.style.width = newWidth + 'px';
        });

        document.addEventListener('mouseup', function () {
            if (isResizing) {
                isResizing = false;
                document.body.classList.remove('no-select');
                document.body.style.cursor = '';
            }
        });
    }
});