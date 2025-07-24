<template>
  <div class="chart-container">
    <Line
      :data="chartData"
      :options="chartOptions"
      :style="{ height: height, width: width }"
    />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
} from 'chart.js'
import { Line } from 'vue-chartjs'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
)

const props = defineProps({
  title: {
    type: String,
    default: 'Chart Title'
  },
  labels: {
    type: Array,
    required: true
  },
  data: {
    type: Array,
    required: true
  },
  height: {
    type: String,
    default: '400px'
  },
  width: {
    type: String,
    default: '100%'
  },
  chartType: {
    type: String,
    default: 'line',
    validator: (value) => ['line', 'area'].includes(value)
  },
  color: {
    type: String,
    default: '#3B82F6' // Blue color
  }
})

const chartData = computed(() => {
  return {
    labels: props.labels,
    datasets: [
      {
        label: props.title,
        data: props.data,
        borderColor: props.color,
        backgroundColor: props.chartType === 'area' 
          ? `${props.color}20` // 20% opacity
          : 'transparent',
        borderWidth: 2,
        fill: props.chartType === 'area',
        tension: 0.4,
        pointBackgroundColor: props.color,
        pointBorderColor: '#1F2937', // Dark border
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        pointHoverBackgroundColor: props.color,
        pointHoverBorderColor: '#1F2937',
        pointHoverBorderWidth: 2
      }
    ]
  }
})

const chartOptions = computed(() => {
  return {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
      intersect: false,
      mode: 'index'
    },
    plugins: {
      title: {
        display: !!props.title,
        text: props.title,
        color: '#F9FAFB',
        font: {
          size: 16,
          weight: '600'
        },
        padding: {
          top: 10,
          bottom: 30
        }
      },
      legend: {
        display: false
      },
      tooltip: {
        backgroundColor: '#1F2937',
        titleColor: '#F9FAFB',
        bodyColor: '#F9FAFB',
        borderColor: '#374151',
        borderWidth: 1,
        cornerRadius: 8,
        displayColors: false,
        titleFont: {
          size: 14,
          weight: '600'
        },
        bodyFont: {
          size: 13
        },
        padding: 12,
        callbacks: {
          title: (context) => {
            return context[0].label
          },
          label: (context) => {
            return `${context.parsed.y}`
          }
        }
      }
    },
    scales: {
      x: {
        grid: {
          color: '#374151',
          drawBorder: false
        },
        ticks: {
          color: '#9CA3AF',
          font: {
            size: 12
          },
          maxTicksLimit: 8
        },
        border: {
          display: false
        }
      },
      y: {
        grid: {
          color: '#374151',
          drawBorder: false
        },
        ticks: {
          color: '#9CA3AF',
          font: {
            size: 12
          },
          maxTicksLimit: 6,
          callback: function(value) {
            if (value >= 1000000) {
              return (value / 1000000).toFixed(1) + 'M'
            }
            if (value >= 1000) {
              return (value / 1000).toFixed(1) + 'K'
            }
            return value
          }
        },
        border: {
          display: false
        }
      }
    },
    elements: {
      point: {
        hoverRadius: 6
      }
    },
    animation: {
      duration: 800,
      easing: 'easeInOutCubic'
    }
  }
})
</script>

<style scoped>
.chart-container {
  background-color: #111827;
  border-radius: 12px;
  padding: 24px;
  border: 1px solid #374151;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
}

/* Lightweight theme styling */
.chart-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, #f63b3b, transparent);
  opacity: 0.5;
}
</style>