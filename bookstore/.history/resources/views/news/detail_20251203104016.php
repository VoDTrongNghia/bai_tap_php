<!-- Breadcrumb -->
<nav class="breadcrumb">
    <div class="container">
        <a href="<?= $baseUrl ?>">Trang chủ</a>
        <span class="separator">/</span>
        <a href="<?= $baseUrl ?>news">Tin tức</a>
        <span class="separator">/</span>
        <span class="current"><?= htmlspecialchars($newsItem['title']) ?></span>
    </div>
</nav>

<!-- News Detail -->
<section class="news-detail-section">
    <div class="container">
        <div class="news-detail-container">
            <div class="news-detail-main">
                <article class="news-detail-article">
                    <header class="news-detail-header">
                        <div class="news-detail-category"><?= htmlspecialchars($newsItem['category']) ?></div>
                        <h1 class="news-detail-title"><?= htmlspecialchars($newsItem['title']) ?></h1>
                        <div class="news-detail-meta">
                            <span class="news-detail-date">
                                <i class="far fa-calendar"></i>
                                <?= date('d/m/Y', strtotime($newsItem['date'])) ?>
                            </span>
                            <span class="news-detail-author">
                                <i class="far fa-user"></i>
                                <?= htmlspecialchars($newsItem['author']) ?>
                            </span>
                            <span class="news-detail-views">
                                <i class="far fa-eye"></i>
                                <?= number_format($newsItem['views']) ?> lượt xem
                            </span>
                        </div>
                    </header>

                    <div class="news-detail-image">
                        <img src="<?= $baseUrl ?>assets/images/news/<?= $newsItem['image'] ?>" 
                             alt="<?= htmlspecialchars($newsItem['title']) ?>"
                             loading="lazy">
                    </div>

                    <div class="news-detail-content">
                        <div class="news-detail-excerpt">
                            <?= htmlspecialchars($newsItem['excerpt']) ?>
                        </div>
                        <div class="news-detail-body">
                            <?= $newsItem['content'] ?>
                        </div>
                    </div>

                    <footer class="news-detail-footer">
                        <div class="news-detail-actions">
                            <button class="btn btn-outline like-btn">
                                <i class="far fa-heart"></i>
                                Thích (<?= $newsItem['likes'] ?>)
                            </button>
                            <button class="btn btn-outline share-btn">
                                <i class="fas fa-share"></i>
                                Chia sẻ
                            </button>
                        </div>
                    </footer>
                </article>

                <!-- Comments Section -->
                <section class="comments-section">
                    <h3 class="comments-title">Bình luận (3)</h3>
                    
                    <div class="comment-form">
                        <h4>Viết bình luận</h4>
                        <form>
                            <div class="form-group">
                                <textarea placeholder="Nhập bình luận của bạn..." rows="4" required></textarea>
                            </div>
                            <div class="form-row">
                                <input type="text" placeholder="Họ tên" required>
                                <input type="email" placeholder="Email" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Gửi bình luận</button>
                        </form>
                    </div>

                    <div class="comments-list">
                        <div class="comment">
                            <div class="comment-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="comment-content">
                                <div class="comment-header">
                                    <span class="comment-author">Nguyễn Văn A</span>
                                    <span class="comment-date">2 ngày trước</span>
                                </div>
                                <p class="comment-text">Bài viết rất hữu ích, cảm ơn thông tin!</p>
                                <button class="comment-reply">Trả lời</button>
                            </div>
                        </div>

                        <div class="comment">
                            <div class="comment-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="comment-content">
                                <div class="comment-header">
                                    <span class="comment-author">Trần Thị B</span>
                                    <span class="comment-date">3 ngày trước</span>
                                </div>
                                <p class="comment-text">Mong có nhiều chương trình khuyến mãi như vậy nữa.</p>
                                <button class="comment-reply">Trả lời</button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Sidebar -->
            <aside class="news-detail-sidebar">
                <!-- Related News -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Tin tức liên quan</h3>
                    <div class="related-news">
                        <?php foreach ($relatedNews as $item): ?>
                            <article class="related-news-item">
                                <h4 class="related-news-title">
                                    <a href="<?= $baseUrl ?>news/detail/<?= $item['id'] ?>">
                                        <?= htmlspecialchars($item['title']) ?>
                                    </a>
                                </h4>
                                <span class="related-news-date">
                                    <i class="far fa-calendar"></i>
                                    <?= date('d/m/Y', strtotime($item['date'])) ?>
                                </span>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Categories -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Danh mục</h3>
                    <ul class="category-list">
                        <li><a href="<?= $baseUrl ?>news?category=sach-moi">Sách mới (15)</a></li>
                        <li><a href="<?= $baseUrl ?>news?category=khuyen-mai">Khuyến mãi (8)</a></li>
                        <li><a href="<?= $baseUrl ?>news?category=su-kien">Sự kiện (12)</a></li>
                        <li><a href="<?= $baseUrl ?>news?category review">Review sách (6)</a></li>
                    </ul>
                </div>

                <!-- Popular Tags -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Tags phổ biến</h3>
                    <div class="tag-cloud">
                        <a href="#" class="tag">sách mới</a>
                        <a href="#" class="tag">khuyến mãi</a>
                        <a href="#" class="tag">sự kiện</a>
                        <a href="#" class="tag">văn học</a>
                        <a href="#" class="tag">review</a>
                        <a href="#" class="tag">tác giả</a>
                        <a href="#" class="tag">workshop</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
