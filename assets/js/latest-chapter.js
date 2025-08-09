jQuery(document).ready(function($) {
    // Hàm để lấy chương mới nhất
    window.getLatestChapter = function(truyenId, callback) {
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'get_latest_chapter',
                truyen_id: truyenId,
                nonce: ajax_object.nonce
            },
            success: function(response) {
                if (response.success) {
                    callback(response.data);
                } else {
                    console.error('Error getting latest chapter:', response.data);
                    callback(null);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                callback(null);
            }
        });
    };

    // Component để hiển thị chương mới nhất
    window.LatestChapterComponent = {
        // Khởi tạo component
        init: function(container, options = {}) {
            const defaults = {
                delay: 100, // Delay giữa các request
                loadingText: '...', // Text hiển thị khi đang tải
                noChapterText: 'Chưa có chương', // Text khi không có chương
                showDate: true, // Có hiển thị ngày không
                dateFormat: 'dd/mm/yyyy' // Format ngày
            };

            this.options = { ...defaults, ...options };
            this.container = container;
            this.queue = [];
            this.isProcessing = false;

            // Tìm tất cả các element cần xử lý
            this.elements = container.find('[data-truyen-id]');
            this.elements.each((index, element) => {
                const $element = $(element);
                const chapterElement = $element.find('.latest-chapter');
                if (chapterElement.length) {
                    chapterElement.text(this.options.loadingText);
                    this.queue.push({
                        element: $element,
                        chapterElement: chapterElement
                    });
                }
            });

            // Bắt đầu xử lý queue
            this.processQueue();
        },

        // Xử lý queue
        processQueue: function() {
            if (this.isProcessing || this.queue.length === 0) return;
            
            this.isProcessing = true;
            const item = this.queue.shift();
            
            getLatestChapter(item.element.data('truyen-id'), (chapter) => {
                if (chapter) {
                    const dateHtml = this.options.showDate ? 
                        `<small>(${chapter.date})</small>` : '';
                    
                    item.chapterElement.html(`
                        <a href="${chapter.link}">
                            ${chapter.title}
                            ${dateHtml}
                        </a>
                    `);
                } else {
                    item.chapterElement.text(this.options.noChapterText);
                }

                this.isProcessing = false;
                setTimeout(() => this.processQueue(), this.options.delay);
            });
        }
    };

    // Khởi tạo component cho tất cả các container có class latest-chapter-container
    $('.latest-chapter-container').each(function() {
        LatestChapterComponent.init($(this));
    });

    // Ví dụ sử dụng:
    // getLatestChapter(truyenId, function(chapter) {
    //     if (chapter) {
    //         console.log('Latest chapter:', chapter);
    //         // Xử lý dữ liệu chương ở đây
    //     }
    // });
}); 