<?php
$page_title = "Home";
require_once 'config/database.php';
require_once 'includes/header.php';

$conn = getDBConnection();

$breaking_query = "SELECT a.*, c.category_name, u.username as author_name 
                  FROM articles a 
                  LEFT JOIN categories c ON a.category_id = c.category_id 
                  LEFT JOIN users u ON a.author_id = u.user_id 
                  ORDER BY a.published_date DESC LIMIT 1";
$breaking_result = $conn->query($breaking_query);
$breaking_news = $breaking_result->fetch_assoc();

$latest_update_query = "SELECT a.*, c.category_name, u.username as author_name 
                       FROM articles a 
                       LEFT JOIN categories c ON a.category_id = c.category_id 
                       LEFT JOIN users u ON a.author_id = u.user_id 
                       ORDER BY a.published_date DESC LIMIT 1 OFFSET 1";
$latest_update_result = $conn->query($latest_update_query);
$latest_update = $latest_update_result->fetch_assoc();

$featured_query = "SELECT a.*, c.category_name, u.username as author_name 
                  FROM articles a 
                  LEFT JOIN categories c ON a.category_id = c.category_id 
                  LEFT JOIN users u ON a.author_id = u.user_id 
                  ORDER BY a.published_date DESC LIMIT 3 OFFSET 2";
$featured_result = $conn->query($featured_query);

$latest_query = "SELECT a.*, c.category_name, u.username as author_name 
                FROM articles a 
                LEFT JOIN categories c ON a.category_id = c.category_id 
                LEFT JOIN users u ON a.author_id = u.user_id 
                ORDER BY a.published_date DESC LIMIT 5 OFFSET 5";
$latest_result = $conn->query($latest_query);

$trending_query = "SELECT a.*, c.category_name 
                  FROM articles a 
                  LEFT JOIN categories c ON a.category_id = c.category_id 
                  ORDER BY RAND() LIMIT 5";
$trending_result = $conn->query($trending_query);
?>

<main class="news-main">
    <div class="news-container">
        <?php if ($breaking_news): ?>
            <section class="news-hero">
                <div class="hero-card">
                    <img src="<?php echo htmlspecialchars($breaking_news['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($breaking_news['title']); ?>" class="hero-image">
                    <div class="hero-content">
                        <span class="news-tag"><?php echo htmlspecialchars($breaking_news['category_name']); ?></span>
                        <h1><a href="article.php?id=<?php echo $breaking_news['article_id']; ?>">
                            <?php echo htmlspecialchars($breaking_news['title']); ?>
                        </a></h1>
                        <p><?php echo substr(strip_tags($breaking_news['content']), 0, 200) . '...'; ?></p>
                        <div class="news-meta">
                            <span>By <?php echo htmlspecialchars($breaking_news['author_name'] ?? 'Admin'); ?></span>
                            <span><?php echo date('M j, Y', strtotime($breaking_news['published_date'])); ?></span>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <div class="news-grid">
            <div class="main-news">
                <section class="latest-news">
                    <h2>Recent Updates</h2>
                    <div class="latest-container">
                        <?php if ($latest_update): ?>
                            <article class="news-card small">
                                <img src="<?php echo htmlspecialchars($latest_update['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($latest_update['title']); ?>" class="news-image">
                                <div class="news-content">
                                    <span class="news-tag"><?php echo htmlspecialchars($latest_update['category_name']); ?></span>
                                    <h3><a href="article.php?id=<?php echo $latest_update['article_id']; ?>">
                                        <?php echo htmlspecialchars($latest_update['title']); ?>
                                    </a></h3>
                                    <p><?php echo substr(strip_tags($latest_update['content']), 0, 100) . '...'; ?></p>
                                    <div class="news-meta">
                                        <span>By <?php echo htmlspecialchars($latest_update['author_name'] ?? 'Admin'); ?></span>
                                        <span><?php echo date('M j, Y', strtotime($latest_update['published_date'])); ?></span>
                                    </div>
                                </div>
                            </article>
                        <?php endif; ?>
                        <?php while ($article = $latest_result->fetch_assoc()): ?>
                            <article class="news-card small">
                                <img src="<?php echo htmlspecialchars($article['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($article['title']); ?>" class="news-image">
                                <div class="news-content">
                                    <span class="news-tag"><?php echo htmlspecialchars($article['category_name']); ?></span>
                                    <h3><a href="article.php?id=<?php echo $article['article_id']; ?>">
                                        <?php echo htmlspecialchars($article['title']); ?>
                                    </a></h3>
                                    <p><?php echo substr(strip_tags($article['content']), 0, 100) . '...'; ?></p>
                                    <div class="news-meta">
                                        <span>By <?php echo htmlspecialchars($article['author_name'] ?? 'Admin'); ?></span>
                                        <span><?php echo date('M j, Y', strtotime($article['published_date'])); ?></span>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </section>

                <section class="featured-news">
                    <h2>Featured Stories</h2>
                    <div class="featured-container">
                        <?php while ($article = $featured_result->fetch_assoc()): ?>
                            <article class="news-card">
                                <img src="<?php echo htmlspecialchars($article['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($article['title']); ?>" class="news-image">
                                <div class="news-content">
                                    <span class="news-tag"><?php echo htmlspecialchars($article['category_name']); ?></span>
                                    <h3><a href="article.php?id=<?php echo $article['article_id']; ?>">
                                        <?php echo htmlspecialchars($article['title']); ?>
                                    </a></h3>
                                    <p><?php echo substr(strip_tags($article['content']), 0, 120) . '...'; ?></p>
                                    <div class="news-meta">
                                        <span>By <?php echo htmlspecialchars($article['author_name'] ?? 'Admin'); ?></span>
                                        <span><?php echo date('M j, Y', strtotime($article['published_date'])); ?></span>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </section>
            </div>

            <aside class="news-sidebar">
                <div class="sidebar-section">
                    <h3>Trending Now</h3>
                    <div class="trending-container">
                        <?php while ($trending = $trending_result->fetch_assoc()): ?>
                            <article class="trending-card">
                                <img src="<?php echo htmlspecialchars($trending['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($trending['title']); ?>" class="trending-image">
                                <div class="trending-content">
                                    <span class="news-tag small"><?php echo htmlspecialchars($trending['category_name']); ?></span>
                                    <h4><a href="article.php?id=<?php echo $trending['article_id']; ?>">
                                        <?php echo htmlspecialchars($trending['title']); ?>
                                    </a></h4>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="sidebar-section">
                    <h3>Newsletter Signup</h3>
                    <p>Join our community for the latest updates.</p>
                    <form class="newsletter-form" action="subscribe.php" method="POST">
                        <input type="email" name="email" placeholder="Enter your email" required>
                        <button type="submit" class="btn-news">Subscribe</button>
                    </form>
                </div>

                <div class="sidebar-section">
                    <h3>Sponsored</h3>
                    <div class="ad-space">
                        <p>Sponsored Content</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>

<?php
$conn->close();
require_once 'includes/footer.php';
?>