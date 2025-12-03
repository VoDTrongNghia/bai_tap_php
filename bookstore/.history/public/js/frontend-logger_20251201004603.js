/**
 * Frontend Logging System
 * Tracks user interactions, errors, and performance metrics
 */

class FrontendLogger {
    constructor(options = {}) {
        this.options = {
            endpoint: options.endpoint || '/api/logs',
            batchSize: options.batchSize || 10,
            flushInterval: options.flushInterval || 5000,
            enableConsoleLog: options.enableConsoleLog || false,
            maxRetries: options.maxRetries || 3,
            ...options
        };

        this.logQueue = [];
        this.sessionId = this.generateSessionId();
        this.startTime = Date.now();
        this.deviceInfo = this.getDeviceInfo();
        this.retryCount = 0;

        this.init();
    }

    init() {
        // Setup global error handlers
        this.setupErrorHandlers();
        
        // Setup user interaction tracking
        this.setupInteractionTracking();
        
        // Setup performance monitoring
        this.setupPerformanceMonitoring();
        
        // Setup periodic flush
        this.setupPeriodicFlush();
        
        // Log session start
        this.log('session_start', {
            url: window.location.href,
            referrer: document.referrer,
            userAgent: navigator.userAgent,
            screen: `${screen.width}x${screen.height}`,
            viewport: `${window.innerWidth}x${window.innerHeight}`
        });
    }

    generateSessionId() {
        return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    getDeviceInfo() {
        return {
            userAgent: navigator.userAgent,
            platform: navigator.platform,
            language: navigator.language,
            cookieEnabled: navigator.cookieEnabled,
            onLine: navigator.onLine,
            screen: {
                width: screen.width,
                height: screen.height,
                colorDepth: screen.colorDepth,
                pixelDepth: screen.pixelDepth
            },
            viewport: {
                width: window.innerWidth,
                height: window.innerHeight
            },
            connection: navigator.connection ? {
                effectiveType: navigator.connection.effectiveType,
                downlink: navigator.connection.downlink,
                rtt: navigator.connection.rtt
            } : null
        };
    }

    setupErrorHandlers() {
        // JavaScript errors
        window.addEventListener('error', (event) => {
            this.log('javascript_error', {
                message: event.message,
                filename: event.filename,
                lineno: event.lineno,
                colno: event.colno,
                stack: event.error ? event.error.stack : null,
                timestamp: Date.now()
            });
        });

        // Promise rejections
        window.addEventListener('unhandledrejection', (event) => {
            this.log('promise_rejection', {
                reason: event.reason,
                stack: event.reason ? event.reason.stack : null,
                timestamp: Date.now()
            });
        });

        // Resource loading errors
        window.addEventListener('error', (event) => {
            if (event.target !== window) {
                this.log('resource_error', {
                    element: event.target.tagName,
                    source: event.target.src || event.target.href,
                    type: event.target.type || 'unknown',
                    timestamp: Date.now()
                });
            }
        }, true);
    }

    setupInteractionTracking() {
        // Click events
        document.addEventListener('click', (event) => {
            const target = event.target;
            const elementInfo = this.getElementInfo(target);
            
            this.log('click', {
                x: event.clientX,
                y: event.clientY,
                target: elementInfo,
                timestamp: Date.now()
            });
        });

        // Scroll events (throttled)
        let scrollTimeout;
        document.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                this.log('scroll', {
                    scrollTop: window.pageYOffset,
                    scrollLeft: window.pageXOffset,
                    documentHeight: document.documentElement.scrollHeight,
                    viewportHeight: window.innerHeight,
                    scrollPercentage: (window.pageYOffset / (document.documentElement.scrollHeight - window.innerHeight)) * 100,
                    timestamp: Date.now()
                });
            }, 100);
        });

        // Image loading events
        document.addEventListener('load', (event) => {
            if (event.target.tagName === 'IMG') {
                this.log('image_load_success', {
                    src: event.target.src,
                    naturalWidth: event.target.naturalWidth,
                    naturalHeight: event.target.naturalHeight,
                    loadTime: Date.now() - (event.target.dataset.startTime || Date.now()),
                    timestamp: Date.now()
                });
            }
        }, true);

        document.addEventListener('error', (event) => {
            if (event.target.tagName === 'IMG') {
                this.log('image_load_error', {
                    src: event.target.src,
                    timestamp: Date.now()
                });
            }
        }, true);

        // Track image load start time
        document.addEventListener('DOMContentLoaded', () => {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                if (img.src && !img.dataset.startTime) {
                    img.dataset.startTime = Date.now().toString();
                }
            });
        });
    }

    setupPerformanceMonitoring() {
        // Monitor page load performance
        window.addEventListener('load', () => {
            if (window.performance && window.performance.timing) {
                const timing = window.performance.timing;
                this.log('page_performance', {
                    domContentLoaded: timing.domContentLoadedEventEnd - timing.domContentLoadedEventStart,
                    loadComplete: timing.loadEventEnd - timing.loadEventStart,
                    totalLoadTime: timing.loadEventEnd - timing.navigationStart,
                    dnsLookup: timing.domainLookupEnd - timing.domainLookupStart,
                    tcpConnect: timing.connectEnd - timing.connectStart,
                    requestTime: timing.responseEnd - timing.requestStart,
                    responseTime: timing.responseEnd - timing.responseStart,
                    domProcessing: timing.domComplete - timing.domLoading,
                    timestamp: Date.now()
                });
            }
        });

        // Monitor long tasks
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                list.getEntries().forEach((entry) => {
                    if (entry.duration > 50) { // Log tasks taking longer than 50ms
                        this.log('long_task', {
                            duration: entry.duration,
                            startTime: entry.startTime,
                            name: entry.name,
                            timestamp: Date.now()
                        });
                    }
                });
            });
            observer.observe({ entryTypes: ['longtask'] });
        }
    }

    setupPeriodicFlush() {
        setInterval(() => {
            this.flush();
        }, this.options.flushInterval);

        // Flush on page unload
        window.addEventListener('beforeunload', () => {
            this.flush(true);
        });
    }

    getElementInfo(element) {
        const info = {
            tagName: element.tagName,
            id: element.id,
            className: element.className,
            textContent: element.textContent ? element.textContent.substring(0, 100) : '',
            href: element.href,
            src: element.src,
            alt: element.alt,
            type: element.type,
            name: element.name,
            value: element.value
        };

        // Get data attributes
        const dataAttrs = {};
        for (let attr of element.attributes) {
            if (attr.name.startsWith('data-')) {
                dataAttrs[attr.name] = attr.value;
            }
        }
        info.dataAttributes = dataAttrs;

        // Get CSS selector path
        info.selector = this.getSelectorPath(element);

        return info;
    }

    getSelectorPath(element) {
        if (!element || element.nodeType !== 1) return '';
        
        const path = [];
        while (element && element.nodeType === 1) {
            let selector = element.nodeName.toLowerCase();
            if (element.id) {
                selector += '#' + element.id;
                path.unshift(selector);
                break;
            } else {
                let sibling = element;
                let siblingIndex = 1;
                while (sibling.previousSibling) {
                    sibling = sibling.previousSibling;
                    if (sibling.nodeType === 1 && sibling.nodeName.toLowerCase() === selector) {
                        siblingIndex++;
                    }
                }
                selector += ':nth-of-type(' + siblingIndex + ')';
                path.unshift(selector);
                element = element.parentNode;
            }
        }
        return path.join(' > ');
    }

    log(eventType, data = {}) {
        const logEntry = {
            sessionId: this.sessionId,
            timestamp: Date.now(),
            eventType: eventType,
            data: data,
            deviceInfo: this.deviceInfo,
            url: window.location.href,
            sessionDuration: Date.now() - this.startTime
        };

        this.logQueue.push(logEntry);

        if (this.options.enableConsoleLog) {
            console.log(`[FrontendLogger] ${eventType}:`, data);
        }

        // Auto flush if queue is full
        if (this.logQueue.length >= this.options.batchSize) {
            this.flush();
        }
    }

    async flush(sync = false) {
        if (this.logQueue.length === 0) return;

        const logs = [...this.logQueue];
        this.logQueue = [];

        try {
            const response = await fetch(this.options.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    logs: logs,
                    timestamp: Date.now()
                }),
                keepalive: sync
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            this.retryCount = 0;
            
            if (this.options.enableConsoleLog) {
                console.log(`[FrontendLogger] Flushed ${logs.length} logs successfully`);
            }
        } catch (error) {
            console.error('[FrontendLogger] Failed to flush logs:', error);
            
            // Retry logic
            if (this.retryCount < this.options.maxRetries) {
                this.retryCount++;
                this.logQueue.unshift(...logs);
                
                setTimeout(() => {
                    this.flush();
                }, Math.pow(2, this.retryCount) * 1000);
            } else {
                console.error('[FrontendLogger] Max retries reached, dropping logs');
            }
        }
    }

    // Public API methods
    trackCustomEvent(eventName, data) {
        this.log('custom_event', {
            eventName: eventName,
            ...data
        });
    }

    trackPerformance(metricName, value, unit = 'ms') {
        this.log('performance_metric', {
            metricName: metricName,
            value: value,
            unit: unit
        });
    }

    trackError(error, context = {}) {
        this.log('tracked_error', {
            message: error.message,
            stack: error.stack,
            context: context
        });
    }
}

// Initialize logger with configuration
window.FrontendLogger = FrontendLogger;
