<template>
    <div class="home-container">
        <!-- Gray Container for Header and Categories -->
        <div class="content-container">
            <!-- Header Section with Pattern Background -->
            <div class="header-section">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <h1 class="heading-1 mb-3">Gutenberg Project</h1>
                            <p class="subtitle body-text">
                                A social cataloging website that allows you to freely search its database of books, annotations, and reviews.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories Section -->
            <div class="categories-section">
                <div class="container">
                    <!-- Loading State -->
                    <div v-if="loading" class="row justify-content-center">
                        <div class="col-12 text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div v-else-if="error" class="row justify-content-center">
                        <div class="col-12 col-md-6 text-center">
                            <div class="error-message">
                                <p class="body-text">{{ error }}</p>
                                <button @click="fetchCategories" class="btn-retry">Retry</button>
                            </div>
                        </div>
                    </div>

                    <!-- Categories Grid -->
                    <div v-else class="row justify-content-center">
                        <div class="col-12 col-md-8 col-lg-6">
                            <div v-for="category in categories" :key="category.id" class="category-row">
                                <button 
                                    @click="goToBooks(category.name)"
                                    class="genre-card"
                                >
                                    <div class="card-content">
                                        <div class="left-content">
                                            <img 
                                                :src="getCategoryIcon(category.name)" 
                                                :alt="category.name"
                                                class="category-icon"
                                                @error="handleImageError"
                                            >
                                            <span class="category-name">{{ category.name }}</span>
                                        </div>
                                        <div class="right-content">
                                            <img 
                                                src="/images/Next.svg" 
                                                alt="Next"
                                                class="next-icon"
                                            >
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Home',
    data() {
        return {
            categories: [],
            loading: true,
            error: null
        }
    },
    async mounted() {
        await this.fetchCategories();
    },
    methods: {
        async fetchCategories() {
            this.loading = true;
            this.error = null;
            
            try {
                const response = await axios.get('/api/categories');
                this.categories = response.data;
            } catch (error) {
                console.error('Error fetching categories:', error);
                this.error = 'Failed to load categories. Please try again.';
                
                // Fallback categories
                this.categories = [
                    { id: 1, name: 'Fiction' },
                    { id: 2, name: 'Drama' },
                    { id: 3, name: 'History' },
                    { id: 4, name: 'Philosophy' },
                    { id: 5, name: 'Politics' },
                    { id: 6, name: 'Adventure' },
                    { id: 7, name: 'Humour' }
                ];
            } finally {
                this.loading = false;
            }
        },
        goToBooks(category) {
            this.$router.push({ 
                name: 'books', 
                query: { topic: category.toLowerCase() } 
            });
        },
        getCategoryIcon(categoryName) {
            const iconMap = {
                'Fiction': '/images/Fiction.svg',
                'Africa': '/images/Africa.svg',
                'Drama': '/images/Drama.svg',
                'History': '/images/History.svg',
                'Philosophy': '/images/philosophy.svg',
                'Politics': '/images/politics.svg',
                'Adventure': '/images/Adventure.svg',
                'Humour': '/images/Humour.svg'
            };
            return iconMap[categoryName];
        },
        handleImageError(event) {
            event.target.src = '/images/pattern.svg';
        }
    }
}
</script>

<style scoped>
.home-container {
    min-height: 100vh;
    background-color: #FFFFFF; /* White background for entire page */
    padding: 0;
}

/* Gray Container for Header and Categories */
.content-container {
    background-color: var(--secondary-color); /* Gray background only for this container */
    min-height: 100vh;
    margin: 0 auto;
    max-width: 100%;
}

/* Header Section with Pattern Background */
.header-section {
    padding: 60px 0 40px 0;
    background-color: var(--secondary-color); /* Gray background */
    background-image: url('/images/pattern.svg');
    background-repeat: no-repeat;
    background-position: center 80px;
    background-size: 200px;
}

.heading-1 {
    color: var(--primary-color);
    text-align: center;
    margin-bottom: 16px;
}

.subtitle {
    color: var(--grey-dark);
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

/* Categories Section */
.categories-section {
    padding: 0 0 60px 0;
    background-color: var(--secondary-color); /* Gray background */
}

.category-row {
    margin-bottom: 16px;
}

/* Genre Card - Exact design from PDF */
.genre-card {
    width: 100%;
    height: 50px;
    background: #FFFFFF;
    border: none;
    border-radius: 4px;
    box-shadow: 0 2px 5px 0 rgba(211, 209, 238, 0.5);
    padding: 0 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: block;
}

.genre-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(211, 209, 238, 0.7);
}

.card-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 100%;
    color: var(--primary-color);
}

.left-content {
    display: flex;
    align-items: center;
    flex: 1;
}

.category-icon {
    width: 24px;
    height: 24px;
    margin-right: 12px;
    flex-shrink: 0;
}

.category-name {
    font-family: 'Montserrat', sans-serif;
    font-weight: 400;
    font-size: 20px;
    line-height: 1;
}

.right-content {
    display: flex;
    align-items: center;
}

.next-icon {
    width: 16px;
    height: 16px;
    opacity: 0.7;
    transition: opacity 0.2s ease;
}

.genre-card:hover .next-icon {
    opacity: 1;
}

/* Error State */
.error-message {
    padding: 20px;
    text-align: center;
    background: #FFFFFF;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.btn-retry {
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 4px;
    padding: 10px 20px;
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
}

.btn-retry:hover {
    background: #4a43d6;
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-section {
        padding: 40px 0 30px 0;
        background-size: 150px;
        background-position: center 60px;
    }
    
    .heading-1 {
        font-size: 36px;
    }
    
    .categories-section {
        padding: 0 0 40px 0;
    }
    
    .genre-card {
        padding: 0 15px;
        height: 45px;
    }
    
    .category-name {
        font-size: 18px;
    }
    
    .category-icon {
        width: 20px;
        height: 20px;
        margin-right: 10px;
    }
    
    .next-icon {
        width: 14px;
        height: 14px;
    }
}

@media (max-width: 480px) {
    .header-section {
        background-size: 120px;
        background-position: center 50px;
    }
    
    .heading-1 {
        font-size: 32px;
    }
    
    .genre-card {
        padding: 0 12px;
    }
    
    .category-name {
        font-size: 16px;
    }
}
</style>