<template>
    <div class="books-container">
        <div class="container">
           
            <div class="page-header">
                <div class="header-content">
                    <button @click="goBack" class="back-button">
                        <img src="/images/back.svg" alt="Back" class="back-icon">
                    </button>
                    <div class="search-container">
                        <input 
                            type="text" 
                            v-model="searchQuery" 
                            @input="handleSearch"
                            placeholder="Search"
                            class="search-box"
                        >
                        <img src="/images/search.svg" alt="Search" class="search-icon">
                        <button 
                            v-if="searchQuery" 
                            @click="clearSearch"
                            class="clear-button"
                        >
                            <img src="/images/cancel.svg" alt="Clear" class="clear-icon">
                        </button>
                    </div>
                </div>
            </div>

         
            <div class="category-title-section">
                <h2 class="heading-2">{{ formattedCategory }}</h2>
            </div>

           
            <div class="books-grid" id="books-grid">
                <div 
                    v-for="book in books" 
                    :key="book.id"
                    class="book-card"
                    @click="openBook(book)"
                >
                    <div class="book-cover">
                      
                        <img 
                            :src="getBookCover(book)" 
                            :alt="book.title"
                            class="book-cover-image"
                            @error="handleCoverError"
                        >
                       
                        <div v-if="!hasCoverImage(book)" class="book-cover-placeholder">
                            <span class="book-initial">{{ getBookInitial(book.title) }}</span>
                        </div>
                    </div>
                    <div class="book-info">
                        <h3 class="book-name text-truncate">{{ book.title }}</h3>
                        <p class="book-author text-truncate">
                            {{ book.authors[0]?.name || 'Unknown Author' }}
                        </p>
                    </div>
                </div>
            </div>

          
            <div v-if="loading" class="loading-section">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <!-- No Results -->
            <div v-if="!loading && books.length === 0" class="no-results">
                <p class="body-text">No books found</p>
            </div>

            <!-- Load More Trigger -->
            <div v-if="nextPageUrl && !loading" class="load-more-section">
                <button @click="loadMore" class="load-more-btn">Load More</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Books',
    data() {
        return {
            books: [],
            loading: false,
            searchQuery: '',
            currentCategory: '',
            nextPageUrl: null,
            searchTimeout: null
        }
    },
    computed: {
        formattedCategory() {
            if (!this.$route.query.topic) return 'All Books';
            return this.$route.query.topic.split(' ')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        }
    },
    watch: {
        '$route.query': {
            immediate: true,
            handler(newQuery) {
                this.currentCategory = newQuery.topic || '';
                this.loadBooks();
            }
        }
    },
    methods: {
        async loadBooks(append = false) {
            if (this.loading) return;

            this.loading = true;
            try {
                const params = new URLSearchParams();
                
                if (this.$route.query.topic) {
                    params.append('topic', this.$route.query.topic);
                }
                
                if (this.searchQuery) {
                    params.append('search', this.searchQuery);
                }

                let response;
                if (append && this.nextPageUrl) {
                    response = await axios.get(this.nextPageUrl);
                } else {
                    response = await axios.get(`/api/books?${params}`);
                }

                if (append) {
                    this.books = [...this.books, ...response.data.results];
                } else {
                    this.books = response.data.results;
                }
                this.nextPageUrl = response.data.next;
            } catch (error) {
                console.error('Error fetching books:', error);
            } finally {
                this.loading = false;
            }
        },
        handleSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.loadBooks(false);
            }, 500);
        },
        clearSearch() {
            this.searchQuery = '';
            this.loadBooks(false);
        },
        goBack() {
            this.$router.push('/');
        },
        openBook(book) {
            const preferredFormats = ['text/html', 'application/pdf', 'text/plain'];
            let bookUrl = null;

            for (const format of preferredFormats) {
                if (book.formats[format] && !book.formats[format].includes('.zip')) {
                    bookUrl = book.formats[format];
                    break;
                }
            }

            if (bookUrl) {
                window.open(bookUrl, '_blank');
            } else {
                alert('No viewable version available');
            }
        },
        loadMore() {
            this.loadBooks(true);
        },
        getBookInitial(title) {
            return title.charAt(0).toUpperCase();
        },
       
        getBookCover(book) {
           
            const gutenbergId = book.id;
            return `https://www.gutenberg.org/cache/epub/${gutenbergId}/pg${gutenbergId}.cover.medium.jpg`;
        },
        
        hasCoverImage(book) {
          
            return Object.keys(book.formats || {}).some(format => 
                format.startsWith('image/')
            );
        },
      
        handleCoverError(event) {
            console.log('Cover image failed to load for book:', event.target.alt);
           
        }
    }
}
</script>

<style scoped>
.books-container {
    min-height: 100vh;
    background-color: var(--secondary-color);
    padding: 24px 0;
}


.page-header {
    margin-bottom: 32px;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 16px;
}

.back-button {
    background: none;
    border: none;
    padding: 8px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.back-button:hover {
    background-color: var(--grey-light);
}

.back-icon {
    width: 24px;
    height: 24px;
}

/* Search Box */
.search-container {
    position: relative;
    flex: 1;
    max-width: 400px;
}

.search-box {
    width: 100%;
    height: 40px;
    border: 1px solid var(--grey-light);
    border-radius: 4px;
    padding: 0 40px;
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    background: #FFFFFF;
    transition: border-color 0.2s;
}

.search-box:focus {
    outline: none;
    border-color: var(--primary-color);
}

.search-box::placeholder {
    color: var(--grey-medium);
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
}

.clear-button {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    padding: 4px;
    cursor: pointer;
    border-radius: 2px;
}

.clear-button:hover {
    background-color: var(--grey-light);
}

.clear-icon {
    width: 16px;
    height: 16px;
}

/* Category Title */
.category-title-section {
    margin-bottom: 32px;
}

.heading-2 {
    color: var(--primary-color);
}

/* Books Grid */
.books-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 32px;
}

/* Book Card */
.book-card {
    width: 114px;
    height: 162px;
    background: #FFFFFF;
    border-radius: 8px;
    box-shadow: 0 2px 5px 0 rgba(211, 209, 238, 0.5);
    cursor: pointer;
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.book-card:hover {
    transform: scale(1.05);
}

.book-cover {
    height: 114px;
    background: var(--grey-light);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.book-cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-cover-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color) 0%, #7a74eb 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.book-initial {
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
    font-size: 24px;
    color: #FFFFFF;
}

.book-info {
    padding: 8px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.book-name {
    color: var(--grey-dark);
    margin: 0 0 2px 0;
    line-height: 1.2;
}

.book-author {
    color: var(--grey-medium);
    margin: 0;
    line-height: 1.2;
}

/* Loading and States */
.loading-section,
.no-results {
    text-align: center;
    padding: 40px 0;
}

.no-results p {
    color: var(--grey-medium);
}

.load-more-section {
    text-align: center;
    padding: 20px 0;
}

.load-more-btn {
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 4px;
    padding: 12px 24px;
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.load-more-btn:hover {
    background: #4a43d6;
}

/* Responsive Design */
@media (min-width: 576px) {
    .books-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 768px) {
    .books-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (min-width: 992px) {
    .books-grid {
        grid-template-columns: repeat(5, 1fr);
    }
}

@media (min-width: 1200px) {
    .books-grid {
        grid-template-columns: repeat(6, 1fr);
    }
}

@media (max-width: 480px) {
    .books-container {
        padding: 16px 0;
    }
    
    .page-header {
        margin-bottom: 24px;
    }
    
    .category-title-section {
        margin-bottom: 24px;
    }
    
    .books-grid {
        gap: 16px;
    }
    
    .book-card {
        width: 100px;
        height: 142px;
    }
    
    .book-cover {
        height: 100px;
    }
}
</style>