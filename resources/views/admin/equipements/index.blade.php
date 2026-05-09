@extends('layouts.playout')

@section('title', 'Gestion des équipements')

<style>
    .action-button {
        transition: all 0.2s ease;
    }
    
    .action-button:hover {
        transform: translateY(-2px);
    }
    
    .table-container {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .table-row {
        transition: all 0.2s ease;
    }
    
    .table-row:hover {
        background-color: rgba(79, 70, 229, 0.05) !important;
    }
    
    .equipment-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: rgba(79, 70, 229, 0.1);
        color: #4F46E5;
    }
    
    .pagination-link {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
    }
    
    .pagination-link.active {
        background-color: #4F46E5;
        color: white;
    }
    
    .search-highlight {
        background-color: rgba(255, 230, 0, 0.3);
        border-radius: 2px;
        padding: 0 2px;
    }
</style>

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 px-4 sm:px-0">
            <div>
                <h2 class="font-bold text-3xl text-gray-800 leading-tight">
                    {{ __('Équipements de l\'hôtel') }}
                </h2>
                <p class="mt-1 text-gray-500">Gérez les équipements et commodités proposés par vos/votre établissement</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.equipements.create') }}" 
                   class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-200 flex items-center shadow-md action-button">
                    <i class="fas fa-plus mr-2"></i>Ajouter un équipement
                </a>
            </div>
        </div>
        
        <!-- Notification -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        <!-- Search Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-1">
                    <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="searchInput" 
                               class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                               placeholder="Recherche instantanée...">
                    </div>
                </div>
                <div>
                    <button id="resetSearch" class="w-full md:w-auto px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 action-button">
                        <i class="fas fa-redo mr-2"></i>Réinitialiser
                    </button>
                </div>
            </div>
        </div>

        <!-- Table View of Equipment -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6 table-container">
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="font-medium text-gray-700">Liste des équipements</h3>
                <div class="text-gray-500 text-sm">
                    <span id="itemCount">{{ count($equipements) }}</span> équipements trouvés
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icône</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="equipmentTableBody">
                        @forelse($equipements as $equipement)
                            <tr class="hover:bg-gray-50 table-row">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="equipment-icon">
                                        <i class="fas fa-{{ $equipement->icone ?? 'concierge-bell' }}"></i>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $equipement->nom }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ Str::limit($equipement->description, 100) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-3">
                                        <a href="{{ route('admin.equipements.edit', $equipement) }}"
                                            class="inline-flex items-center px-3 py-1 border border-indigo-100 bg-indigo-50 text-indigo-600 rounded-md hover:bg-indigo-100 action-button">
                                            <i class="fas fa-edit mr-1"></i> Modifier
                                        </a>
                                        <form action="{{ route('admin.equipements.destroy', $equipement) }}"
                                               method="POST"
                                               class="inline-block"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet équipement ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1 border border-red-100 bg-red-50 text-red-600 rounded-md hover:bg-red-100 action-button">
                                                <i class="fas fa-trash mr-1"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    <div class="text-gray-400 mb-4">
                                        <i class="fas fa-hotel text-5xl"></i>
                                    </div>
                                    <h3 class="text-xl font-medium text-gray-800 mb-2">Aucun équipement trouvé</h3>
                                    <p class="text-gray-500 mb-6">Commencez par ajouter des équipements à votre hôtel</p>
                                    <a href="{{ route('admin.equipements.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 action-button">
                                        <i class="fas fa-plus mr-2"></i>Ajouter un équipement
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            @if(method_exists($equipements, 'links'))
                {{ $equipements->links() }}
            @endif
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const resetButton = document.getElementById('resetSearch');
        const tableBody = document.getElementById('equipmentTableBody');
        const itemCount = document.getElementById('itemCount');
        const rows = Array.from(tableBody.querySelectorAll('tr'));
        
        // Store original table data for reset
        const originalTableData = rows.map(row => {
            return {
                element: row,
                name: row.querySelector('td:nth-child(2)')?.textContent.trim() || '',
                description: row.querySelector('td:nth-child(3)')?.textContent.trim() || ''
            };
        });
        
        // Highlight matching text
        function highlightText(element, text, searchTerm) {
            if (!searchTerm.trim()) return text;
            
            const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            return text.replace(regex, '<span class="search-highlight">$1</span>');
        }
        
        // Search function
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;
            
            // If empty search term, show all rows
            if (!searchTerm) {
                originalTableData.forEach(item => {
                    item.element.style.display = '';
                    
                    // Restore original text (remove highlights)
                    const nameCell = item.element.querySelector('td:nth-child(2)');
                    const descCell = item.element.querySelector('td:nth-child(3)');
                    
                    if (nameCell) nameCell.textContent = item.name;
                    if (descCell) descCell.textContent = item.description;
                });
                
                visibleCount = originalTableData.length;
            } else {
                // Filter rows based on search term
                originalTableData.forEach(item => {
                    const nameMatch = item.name.toLowerCase().includes(searchTerm);
                    const descMatch = item.description.toLowerCase().includes(searchTerm);
                    
                    if (nameMatch || descMatch) {
                        item.element.style.display = '';
                        visibleCount++;
                        
                        // Highlight matching text
                        const nameCell = item.element.querySelector('td:nth-child(2)');
                        const descCell = item.element.querySelector('td:nth-child(3)');
                        
                        if (nameCell) {
                            nameCell.innerHTML = highlightText(nameCell, item.name, searchTerm);
                        }
                        
                        if (descCell) {
                            descCell.innerHTML = highlightText(descCell, item.description, searchTerm);
                        }
                    } else {
                        item.element.style.display = 'none';
                    }
                });
            }
            
            // Update item count
            itemCount.textContent = visibleCount;
            
            // Show empty state if no results
            const emptyRow = tableBody.querySelector('tr[colspan="4"]');
            if (visibleCount === 0 && !emptyRow) {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-search text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-medium text-gray-800 mb-2">Aucun résultat trouvé</h3>
                        <p class="text-gray-500">Essayez avec d'autres termes de recherche</p>
                    </td>
                `;
                tableBody.appendChild(newRow);
            } else if (visibleCount > 0 && emptyRow) {
                emptyRow.remove();
            }
        }
        
        // Event listeners
        searchInput.addEventListener('input', performSearch);
        
        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            performSearch();
            searchInput.focus();
        });
    });
</script>